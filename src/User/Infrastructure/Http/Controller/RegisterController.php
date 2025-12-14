<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Http\Controller;

use App\User\Application\Command\RegisterUser\RegisterUserCommand;
use App\User\Application\DTO\RegisterUserRequest;
use App\User\Application\DTO\UserResponse;
use App\User\Application\Transformer\UserTransformer;
use App\User\Domain\Repository\UserRepositoryInterface;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class RegisterController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $commandBus,
        private readonly UserRepositoryInterface $userRepository,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly Manager $fractal
    ) {
    }

    #[Route('/api/auth/register', name: 'api_auth_register', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $requestDto = $this->serializer->deserialize(
            $request->getContent(),
            RegisterUserRequest::class,
            'json'
        );

        $violations = $this->validator->validate($requestDto);
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()][] = $violation->getMessage();
            }
            
            return $this->json([
                'error' => 'Validation failed',
                'violations' => $errors
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $command = new RegisterUserCommand(
                $requestDto->username,
                $requestDto->email,
                $requestDto->password
            );

            $envelope = $this->commandBus->dispatch($command);
            $handledStamp = $envelope->last(HandledStamp::class);
            $userId = $handledStamp?->getResult();

            $user = $this->userRepository->findById($userId);
            
            $userResponse = new UserResponse(
                $user->id()->value(),
                $user->username()->value(),
                $user->email()->value(),
                $user->role()->value,
                $user->isActive(),
                $user->createdAt()
            );

            $resource = new Item($userResponse, new UserTransformer(), 'user');
            $data = $this->fractal->createData($resource)->toArray();

            return $this->json($data, Response::HTTP_CREATED);
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'error' => 'Validation error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        } catch (\DomainException $e) {
            return $this->json([
                'error' => 'Registration failed',
                'message' => $e->getMessage()
            ], Response::HTTP_CONFLICT);
        } catch (\Exception $e) {
            $previous = $e->getPrevious();
            
            if ($previous instanceof \DomainException) {
                return $this->json([
                    'error' => 'Registration failed',
                    'message' => $previous->getMessage()
                ], Response::HTTP_CONFLICT);
            }
            
            if ($previous instanceof \InvalidArgumentException) {
                return $this->json([
                    'error' => 'Validation error',
                    'message' => $previous->getMessage()
                ], Response::HTTP_BAD_REQUEST);
            }
            
            return $this->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

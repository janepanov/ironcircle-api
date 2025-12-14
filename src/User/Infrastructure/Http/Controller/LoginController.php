<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Http\Controller;

use App\User\Application\DTO\LoginRequest;
use App\User\Application\DTO\LoginResponse;
use App\User\Application\DTO\UserResponse;
use App\User\Application\Query\AuthenticateUser\AuthenticateUserQuery;
use App\User\Application\Transformer\UserTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class LoginController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $queryBus,
        private readonly JWTTokenManagerInterface $jwtManager,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly Manager $fractal
    ) {
    }

    #[Route('/api/auth/login', name: 'api_auth_login', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $requestDto = $this->serializer->deserialize(
            $request->getContent(),
            LoginRequest::class,
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
            $query = new AuthenticateUserQuery(
                $requestDto->email,
                $requestDto->password
            );

            $envelope = $this->queryBus->dispatch($query);
            $handledStamp = $envelope->last(HandledStamp::class);
            $securityUser = $handledStamp?->getResult();

            $token = $this->jwtManager->create($securityUser);

            $userResponse = new UserResponse(
                $securityUser->getId(),
                $securityUser->getUsername(),
                $securityUser->getEmail(),
                $securityUser->getRoles()[0] ?? 'ROLE_MEMBER',
                $securityUser->isActive(),
                new \DateTimeImmutable()
            );

            $userResource = new Item($userResponse, new UserTransformer(), 'user');
            $userData = $this->fractal->createData($userResource)->toArray();

            return $this->json([
                'token' => $token,
                'user' => $userData['data']
            ], Response::HTTP_OK);

        } catch (BadCredentialsException $e) {
            return $this->json([
                'error' => 'Authentication failed',
                'message' => $e->getMessage()
            ], Response::HTTP_UNAUTHORIZED);
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'error' => 'Validation error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            $previous = $e->getPrevious();
            
            if ($previous instanceof BadCredentialsException) {
                return $this->json([
                    'error' => 'Authentication failed',
                    'message' => 'Invalid credentials'
                ], Response::HTTP_UNAUTHORIZED);
            }
            
            return $this->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

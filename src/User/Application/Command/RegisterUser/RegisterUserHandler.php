<?php

declare(strict_types=1);

namespace App\User\Application\Command\RegisterUser;

use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\Username;
use App\User\Domain\Event\UserCreated;
use App\User\Domain\Exception\UserAlreadyExistsException;
use App\User\Domain\Model\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\UserId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsMessageHandler(bus: 'command.bus')]
final readonly class RegisterUserHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private MessageBusInterface $eventBus
    ) {
    }

    public function __invoke(RegisterUserCommand $command): UserId
    {
        $email = new Email($command->email);
        $username = new Username($command->username);

        if ($this->userRepository->existsByEmail($email)) {
            throw UserAlreadyExistsException::withEmail($email->value());
        }

        if ($this->userRepository->existsByUsername($username)) {
            throw UserAlreadyExistsException::withUsername($username->value());
        }

        $userId = UserId::generate();
        $hashedPassword = $this->hashPassword($command->password);
        
        $user = User::create(
            $userId,
            $username,
            $email,
            $hashedPassword
        );

        $this->userRepository->save($user);

        $this->eventBus->dispatch(
            new UserCreated($userId, $username->value(), $email->value())
        );

        return $userId;
    }

    private function hashPassword(string $plainPassword): string
    {
        $tempUser = new \Symfony\Component\Security\Core\User\InMemoryUser('temp', $plainPassword);
        
        return $this->passwordHasher->hashPassword($tempUser, $plainPassword);
    }
}

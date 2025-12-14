<?php

declare(strict_types=1);

namespace App\User\Application\Query\AuthenticateUser;

use App\Shared\Domain\Exception\EntityNotFoundException;
use App\Shared\Domain\ValueObject\Email;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Infrastructure\Security\SecurityUser;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

#[AsMessageHandler(bus: 'query.bus')]
final readonly class AuthenticateUserHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function __invoke(AuthenticateUserQuery $query): SecurityUser
    {
        $email = new Email($query->email);
        
        $user = $this->userRepository->findByEmail($email);
        
        if ($user === null) {
            throw new BadCredentialsException('Invalid credentials');
        }

        if (!$user->isActive()) {
            throw new BadCredentialsException('User account is not active');
        }

        $securityUser = SecurityUser::fromDomain($user);

        // Verify password
        if (!$this->passwordHasher->isPasswordValid($securityUser, $query->password)) {
            throw new BadCredentialsException('Invalid credentials');
        }

        return $securityUser;
    }
}

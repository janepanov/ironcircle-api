<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Security;

use App\User\Domain\Model\User;
use App\User\Domain\ValueObject\UserId;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class SecurityUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    private function __construct(
        private readonly string $id,
        private readonly string $email,
        private readonly string $username,
        private readonly string $password,
        private readonly array $roles,
        private readonly bool $isActive
    ) {
    }

    public static function fromDomain(User $user): self
    {
        return new self(
            $user->id()->value(),
            $user->email()->value(),
            $user->username()->value(),
            $user->passwordHash(),
            [$user->role()->value],
            $user->isActive()
        );
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_MEMBER
        $roles[] = 'ROLE_MEMBER';

        return array_unique($roles);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function eraseCredentials(): void
    {
        // Nothing to erase as we don't store sensitive data
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUserId(): UserId
    {
        return UserId::fromString($this->id);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}

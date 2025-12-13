<?php

declare(strict_types=1);

namespace App\User\Domain\Model;

use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\Username;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\ValueObject\UserRole;
use DateTimeImmutable;
use InvalidArgumentException;

final class User
{
    private UserId $id;
    private Username $username;
    private Email $email;
    private string $passwordHash;
    private UserRole $role;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $updatedAt;
    private bool $isActive;

    private function __construct(
        UserId $id,
        Username $username,
        Email $email,
        string $passwordHash,
        UserRole $role,
        DateTimeImmutable $createdAt,
        bool $isActive = true
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->role = $role;
        $this->createdAt = $createdAt;
        $this->updatedAt = null;
        $this->isActive = $isActive;
    }

    public static function create(
        UserId $id,
        Username $username,
        Email $email,
        string $passwordHash,
        ?UserRole $role = null
    ): self {
        if (empty($passwordHash)) {
            throw new InvalidArgumentException('Password hash cannot be empty');
        }

        return new self(
            $id,
            $username,
            $email,
            $passwordHash,
            $role ?? UserRole::MEMBER,
            new DateTimeImmutable()
        );
    }

    public function changeUsername(Username $newUsername): void
    {
        $this->username = $newUsername;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function changeEmail(Email $newEmail): void
    {
        $this->email = $newEmail;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function changePassword(string $newPasswordHash): void
    {
        if (empty($newPasswordHash)) {
            throw new InvalidArgumentException('Password hash cannot be empty');
        }

        $this->passwordHash = $newPasswordHash;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function promoteToRole(UserRole $role): void
    {
        $this->role = $role;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function deactivate(): void
    {
        $this->isActive = false;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function activate(): void
    {
        $this->isActive = true;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function username(): Username
    {
        return $this->username;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function passwordHash(): string
    {
        return $this->passwordHash;
    }

    public function role(): UserRole
    {
        return $this->role;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function hasRole(UserRole $role): bool
    {
        return $this->role === $role;
    }

    public function canModerate(): bool
    {
        return $this->role->canModerate();
    }
}

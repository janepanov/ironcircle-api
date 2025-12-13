<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Doctrine\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use DateTimeImmutable;

#[MongoDB\Document(collection: 'users')]
#[MongoDB\Index(keys: ['email' => 'asc'], options: ['unique' => true])]
#[MongoDB\Index(keys: ['username' => 'asc'], options: ['unique' => true])]
#[MongoDB\Index(keys: ['isActive' => 'asc'])]
#[MongoDB\Index(keys: ['createdAt' => 'desc'])]
class UserDocument
{
    #[MongoDB\Id(strategy: 'NONE', type: 'string')]
    private string $id;

    #[MongoDB\Field(type: 'string')]
    private string $username;

    #[MongoDB\Field(type: 'string')]
    private string $email;

    #[MongoDB\Field(type: 'string')]
    private string $passwordHash;

    #[MongoDB\Field(type: 'string')]
    private string $role;

    #[MongoDB\Field(type: 'date_immutable')]
    private DateTimeImmutable $createdAt;

    #[MongoDB\Field(type: 'date_immutable', nullable: true)]
    private ?DateTimeImmutable $updatedAt;

    #[MongoDB\Field(type: 'bool')]
    private bool $isActive;

    public function __construct(
        string $id,
        string $username,
        string $email,
        string $passwordHash,
        string $role,
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

    public function getId(): string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function setPasswordHash(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }
}

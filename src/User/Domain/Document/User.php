<?php

declare(strict_types=1);

namespace App\User\Domain\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'users')]
#[ODM\Index(keys: ['email' => 'asc'], unique: true)]
#[ODM\Index(keys: ['status' => 'asc'])]
class User
{
    #[ODM\Id]
    private string $id;

    #[ODM\Field(type: 'string')]
    private string $email;

    #[ODM\Field(type: 'string')]
    private string $passwordHash;

    #[ODM\Field(type: 'collection')]
    private array $roles = ['ROLE_USER'];

    #[ODM\Field(type: 'date')]
    private \DateTimeInterface $createdAt;

    #[ODM\Field(type: 'string')]
    private string $status;

    public function __construct(
        string $email,
        string $passwordHash,
        string $status = 'active'
    ) {
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->status = $status;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function addRole(string $role): void
    {
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function ban(): void
    {
        $this->status = 'banned';
    }

    public function activate(): void
    {
        $this->status = 'active';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isBanned(): bool
    {
        return $this->status === 'banned';
    }
}

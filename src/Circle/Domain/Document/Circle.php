<?php

declare(strict_types=1);

namespace App\Circle\Domain\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'circles')]
#[ODM\Index(keys: ['name' => 'asc'])]
#[ODM\Index(keys: ['ownerId' => 'asc'])]
#[ODM\Index(keys: ['isPrivate' => 'asc'])]
class Circle
{
    #[ODM\Id]
    private string $id;

    #[ODM\Field(type: 'string')]
    private string $name;

    #[ODM\Field(type: 'string')]
    private string $description;

    #[ODM\Field(type: 'string')]
    private string $ownerId;

    #[ODM\Field(type: 'collection')]
    private array $memberIds = [];

    #[ODM\Field(type: 'date')]
    private \DateTimeInterface $createdAt;

    #[ODM\Field(type: 'bool')]
    private bool $isPrivate;

    public function __construct(
        string $name,
        string $description,
        string $ownerId,
        bool $isPrivate = false
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->ownerId = $ownerId;
        $this->isPrivate = $isPrivate;
        $this->createdAt = new \DateTimeImmutable();
        $this->memberIds = [$ownerId];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getOwnerId(): string
    {
        return $this->ownerId;
    }

    public function getMemberIds(): array
    {
        return $this->memberIds;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function isPrivate(): bool
    {
        return $this->isPrivate;
    }

    public function addMember(string $userId): void
    {
        if (!in_array($userId, $this->memberIds, true)) {
            $this->memberIds[] = $userId;
        }
    }

    public function removeMember(string $userId): void
    {
        $this->memberIds = array_values(array_filter(
            $this->memberIds,
            fn($id) => $id !== $userId
        ));
    }

    public function isMember(string $userId): bool
    {
        return in_array($userId, $this->memberIds, true);
    }

    public function isOwner(string $userId): bool
    {
        return $this->ownerId === $userId;
    }

    public function updateDetails(string $name, string $description): void
    {
        $this->name = $name;
        $this->description = $description;
    }

    public function makePrivate(): void
    {
        $this->isPrivate = true;
    }

    public function makePublic(): void
    {
        $this->isPrivate = false;
    }
}

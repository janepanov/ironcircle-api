<?php

declare(strict_types=1);

namespace App\Circle\Infrastructure\Doctrine\Document;

use DateTimeImmutable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(collection: 'circles')]
#[MongoDB\Index(keys: ['slug' => 'asc'], options: ['unique' => true])]
#[MongoDB\Index(keys: ['isActive' => 'asc'])]
#[MongoDB\Index(keys: ['createdAt' => 'desc'])]
#[MongoDB\Index(keys: ['memberCount' => 'desc'])]
#[MongoDB\Index(keys: ['postCount' => 'desc'])]
class CircleDocument
{
    #[MongoDB\Id(strategy: 'NONE', type: 'string')]
    private string $id;

    #[MongoDB\Field(type: 'string')]
    private string $name;

    #[MongoDB\Field(type: 'string')]
    private string $slug;

    #[MongoDB\Field(type: 'string')]
    private string $description;

    #[MongoDB\Field(type: 'string')]
    private string $creatorId;

    #[MongoDB\Field(type: 'collection')]
    private array $moderatorIds;

    #[MongoDB\Field(type: 'int')]
    private int $memberCount;

    #[MongoDB\Field(type: 'int')]
    private int $postCount;

    #[MongoDB\Field(type: 'date_immutable')]
    private DateTimeImmutable $createdAt;

    #[MongoDB\Field(type: 'date_immutable', nullable: true)]
    private ?DateTimeImmutable $updatedAt;

    #[MongoDB\Field(type: 'bool')]
    private bool $isActive;

    public function __construct(
        string $id,
        string $name,
        string $slug,
        string $description,
        string $creatorId,
        array $moderatorIds,
        int $memberCount,
        int $postCount,
        DateTimeImmutable $createdAt,
        bool $isActive = true
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
        $this->description = $description;
        $this->creatorId = $creatorId;
        $this->moderatorIds = $moderatorIds;
        $this->memberCount = $memberCount;
        $this->postCount = $postCount;
        $this->createdAt = $createdAt;
        $this->updatedAt = null;
        $this->isActive = $isActive;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getCreatorId(): string
    {
        return $this->creatorId;
    }

    public function getModeratorIds(): array
    {
        return $this->moderatorIds;
    }

    public function setModeratorIds(array $moderatorIds): void
    {
        $this->moderatorIds = $moderatorIds;
    }

    public function getMemberCount(): int
    {
        return $this->memberCount;
    }

    public function setMemberCount(int $memberCount): void
    {
        $this->memberCount = $memberCount;
    }

    public function getPostCount(): int
    {
        return $this->postCount;
    }

    public function setPostCount(int $postCount): void
    {
        $this->postCount = $postCount;
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

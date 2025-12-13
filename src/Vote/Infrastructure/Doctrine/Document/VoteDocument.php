<?php

declare(strict_types=1);

namespace App\Vote\Infrastructure\Doctrine\Document;

use DateTimeImmutable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(collection: 'votes')]
#[MongoDB\Index(
    keys: ['userId' => 'asc', 'votableId' => 'asc', 'votableType' => 'asc'],
    options: ['unique' => true]
)]
#[MongoDB\Index(keys: ['votableId' => 'asc', 'votableType' => 'asc'])]
#[MongoDB\Index(keys: ['userId' => 'asc'])]
class VoteDocument
{
    #[MongoDB\Id(strategy: 'NONE', type: 'string')]
    private string $id;

    #[MongoDB\Field(type: 'string')]
    private string $userId;

    #[MongoDB\Field(type: 'string')]
    private string $votableId;

    #[MongoDB\Field(type: 'string')]
    private string $votableType;

    #[MongoDB\Field(type: 'int')]
    private int $voteType;

    #[MongoDB\Field(type: 'date_immutable')]
    private DateTimeImmutable $createdAt;

    #[MongoDB\Field(type: 'date_immutable', nullable: true)]
    private ?DateTimeImmutable $updatedAt;

    public function __construct(
        string $id,
        string $userId,
        string $votableId,
        string $votableType,
        int $voteType,
        DateTimeImmutable $createdAt
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->votableId = $votableId;
        $this->votableType = $votableType;
        $this->voteType = $voteType;
        $this->createdAt = $createdAt;
        $this->updatedAt = null;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getVotableId(): string
    {
        return $this->votableId;
    }

    public function getVotableType(): string
    {
        return $this->votableType;
    }

    public function getVoteType(): int
    {
        return $this->voteType;
    }

    public function setVoteType(int $voteType): void
    {
        $this->voteType = $voteType;
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
}

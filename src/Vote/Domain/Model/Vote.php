<?php

declare(strict_types=1);

namespace App\Vote\Domain\Model;

use App\User\Domain\ValueObject\UserId;
use App\Vote\Domain\ValueObject\VotableType;
use App\Vote\Domain\ValueObject\VoteId;
use App\Vote\Domain\ValueObject\VoteType;
use DateTimeImmutable;

final class Vote
{
    private VoteId $id;
    private UserId $userId;
    private string $votableId;
    private VotableType $votableType;
    private VoteType $voteType;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $updatedAt;

    private function __construct(
        VoteId $id,
        UserId $userId,
        string $votableId,
        VotableType $votableType,
        VoteType $voteType,
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

    public static function create(
        VoteId $id,
        UserId $userId,
        string $votableId,
        VotableType $votableType,
        VoteType $voteType
    ): self {
        return new self(
            $id,
            $userId,
            $votableId,
            $votableType,
            $voteType,
            new DateTimeImmutable()
        );
    }

    public function changeVoteType(VoteType $newVoteType): void
    {
        $this->voteType = $newVoteType;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function id(): VoteId
    {
        return $this->id;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function votableId(): string
    {
        return $this->votableId;
    }

    public function votableType(): VotableType
    {
        return $this->votableType;
    }

    public function voteType(): VoteType
    {
        return $this->voteType;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}

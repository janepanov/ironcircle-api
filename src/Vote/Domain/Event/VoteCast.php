<?php

declare(strict_types=1);

namespace App\Vote\Domain\Event;

use App\Shared\Domain\Event\DomainEvent;
use App\User\Domain\ValueObject\UserId;
use App\Vote\Domain\ValueObject\VotableType;
use App\Vote\Domain\ValueObject\VoteId;
use App\Vote\Domain\ValueObject\VoteType;

final class VoteCast extends DomainEvent
{
    public function __construct(
        private readonly VoteId $voteId,
        private readonly UserId $userId,
        private readonly string $votableId,
        private readonly VotableType $votableType,
        private readonly VoteType $voteType
    ) {
        parent::__construct();
    }

    public function voteId(): VoteId
    {
        return $this->voteId;
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

    public function eventName(): string
    {
        return 'vote.cast';
    }
}

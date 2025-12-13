<?php

declare(strict_types=1);

namespace App\Circle\Domain\Event;

use App\Circle\Domain\ValueObject\CircleId;
use App\Shared\Domain\Event\DomainEvent;
use App\User\Domain\ValueObject\UserId;

final class CircleCreated extends DomainEvent
{
    public function __construct(
        private readonly CircleId $circleId,
        private readonly string $name,
        private readonly string $slug,
        private readonly UserId $creatorId
    ) {
        parent::__construct();
    }

    public function circleId(): CircleId
    {
        return $this->circleId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function creatorId(): UserId
    {
        return $this->creatorId;
    }

    public function eventName(): string
    {
        return 'circle.created';
    }
}

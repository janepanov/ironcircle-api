<?php

declare(strict_types=1);

namespace App\Post\Domain\Event;

use App\Circle\Domain\ValueObject\CircleId;
use App\Post\Domain\ValueObject\PostId;
use App\Shared\Domain\Event\DomainEvent;
use App\User\Domain\ValueObject\UserId;

final class PostCreated extends DomainEvent
{
    public function __construct(
        private readonly PostId $postId,
        private readonly CircleId $circleId,
        private readonly UserId $authorId,
        private readonly string $title
    ) {
        parent::__construct();
    }

    public function postId(): PostId
    {
        return $this->postId;
    }

    public function circleId(): CircleId
    {
        return $this->circleId;
    }

    public function authorId(): UserId
    {
        return $this->authorId;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function eventName(): string
    {
        return 'post.created';
    }
}

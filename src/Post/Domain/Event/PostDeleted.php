<?php

declare(strict_types=1);

namespace App\Post\Domain\Event;

use App\Post\Domain\ValueObject\PostId;
use App\Shared\Domain\Event\DomainEvent;

final class PostDeleted extends DomainEvent
{
    public function __construct(
        private readonly PostId $postId
    ) {
        parent::__construct();
    }

    public function postId(): PostId
    {
        return $this->postId;
    }

    public function eventName(): string
    {
        return 'post.deleted';
    }
}

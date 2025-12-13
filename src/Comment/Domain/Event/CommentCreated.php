<?php

declare(strict_types=1);

namespace App\Comment\Domain\Event;

use App\Comment\Domain\ValueObject\CommentId;
use App\Post\Domain\ValueObject\PostId;
use App\Shared\Domain\Event\DomainEvent;
use App\User\Domain\ValueObject\UserId;

final class CommentCreated extends DomainEvent
{
    public function __construct(
        private readonly CommentId $commentId,
        private readonly PostId $postId,
        private readonly UserId $authorId
    ) {
        parent::__construct();
    }

    public function commentId(): CommentId
    {
        return $this->commentId;
    }

    public function postId(): PostId
    {
        return $this->postId;
    }

    public function authorId(): UserId
    {
        return $this->authorId;
    }

    public function eventName(): string
    {
        return 'comment.created';
    }
}

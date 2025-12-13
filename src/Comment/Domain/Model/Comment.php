<?php

declare(strict_types=1);

namespace App\Comment\Domain\Model;

use App\Comment\Domain\ValueObject\CommentContent;
use App\Comment\Domain\ValueObject\CommentId;
use App\Post\Domain\ValueObject\PostId;
use App\User\Domain\ValueObject\UserId;
use DateTimeImmutable;

final class Comment
{
    private CommentId $id;
    private PostId $postId;
    private UserId $authorId;
    private CommentContent $content;
    private int $voteScore;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $updatedAt;
    private ?DateTimeImmutable $deletedAt;

    private function __construct(
        CommentId $id,
        PostId $postId,
        UserId $authorId,
        CommentContent $content,
        DateTimeImmutable $createdAt,
        int $voteScore = 0
    ) {
        $this->id = $id;
        $this->postId = $postId;
        $this->authorId = $authorId;
        $this->content = $content;
        $this->voteScore = $voteScore;
        $this->createdAt = $createdAt;
        $this->updatedAt = null;
        $this->deletedAt = null;
    }

    public static function create(
        CommentId $id,
        PostId $postId,
        UserId $authorId,
        CommentContent $content
    ): self {
        return new self(
            $id,
            $postId,
            $authorId,
            $content,
            new DateTimeImmutable()
        );
    }

    public function updateContent(CommentContent $content): void
    {
        $this->content = $content;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function incrementVoteScore(): void
    {
        $this->voteScore++;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function decrementVoteScore(): void
    {
        $this->voteScore--;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function softDelete(): void
    {
        $this->deletedAt = new DateTimeImmutable();
    }

    public function restore(): void
    {
        $this->deletedAt = null;
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }

    public function isAuthor(UserId $userId): bool
    {
        return $this->authorId->equals($userId);
    }

    public function id(): CommentId
    {
        return $this->id;
    }

    public function postId(): PostId
    {
        return $this->postId;
    }

    public function authorId(): UserId
    {
        return $this->authorId;
    }

    public function content(): CommentContent
    {
        return $this->content;
    }

    public function voteScore(): int
    {
        return $this->voteScore;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }
}

<?php

declare(strict_types=1);

namespace App\Post\Domain\Model;

use App\Circle\Domain\ValueObject\CircleId;
use App\Post\Domain\ValueObject\PostContent;
use App\Post\Domain\ValueObject\PostId;
use App\Post\Domain\ValueObject\PostTitle;
use App\User\Domain\ValueObject\UserId;
use DateTimeImmutable;

final class Post
{
    private PostId $id;
    private CircleId $circleId;
    private UserId $authorId;
    private PostTitle $title;
    private PostContent $content;
    private array $imageUrls;
    private int $voteScore;
    private int $commentCount;
    private ?string $aiSummary;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $updatedAt;
    private ?DateTimeImmutable $deletedAt;
    private bool $isPinned;

    private function __construct(
        PostId $id,
        CircleId $circleId,
        UserId $authorId,
        PostTitle $title,
        PostContent $content,
        array $imageUrls,
        DateTimeImmutable $createdAt,
        int $voteScore = 0,
        int $commentCount = 0,
        ?string $aiSummary = null,
        bool $isPinned = false
    ) {
        $this->id = $id;
        $this->circleId = $circleId;
        $this->authorId = $authorId;
        $this->title = $title;
        $this->content = $content;
        $this->imageUrls = $imageUrls;
        $this->voteScore = $voteScore;
        $this->commentCount = $commentCount;
        $this->aiSummary = $aiSummary;
        $this->createdAt = $createdAt;
        $this->updatedAt = null;
        $this->deletedAt = null;
        $this->isPinned = $isPinned;
    }

    public static function create(
        PostId $id,
        CircleId $circleId,
        UserId $authorId,
        PostTitle $title,
        PostContent $content,
        array $imageUrls = []
    ): self {
        return new self(
            $id,
            $circleId,
            $authorId,
            $title,
            $content,
            $imageUrls,
            new DateTimeImmutable()
        );
    }

    public function updateContent(PostTitle $title, PostContent $content, array $imageUrls = []): void
    {
        $this->title = $title;
        $this->content = $content;
        $this->imageUrls = $imageUrls;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function setAiSummary(string $summary): void
    {
        $this->aiSummary = $summary;
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

    public function incrementCommentCount(): void
    {
        $this->commentCount++;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function decrementCommentCount(): void
    {
        if ($this->commentCount > 0) {
            $this->commentCount--;
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    public function pin(): void
    {
        $this->isPinned = true;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function unpin(): void
    {
        $this->isPinned = false;
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

    public function id(): PostId
    {
        return $this->id;
    }

    public function circleId(): CircleId
    {
        return $this->circleId;
    }

    public function authorId(): UserId
    {
        return $this->authorId;
    }

    public function title(): PostTitle
    {
        return $this->title;
    }

    public function content(): PostContent
    {
        return $this->content;
    }

    public function imageUrls(): array
    {
        return $this->imageUrls;
    }

    public function voteScore(): int
    {
        return $this->voteScore;
    }

    public function commentCount(): int
    {
        return $this->commentCount;
    }

    public function aiSummary(): ?string
    {
        return $this->aiSummary;
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

    public function isPinned(): bool
    {
        return $this->isPinned;
    }
}

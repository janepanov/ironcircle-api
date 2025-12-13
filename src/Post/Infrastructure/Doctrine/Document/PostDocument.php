<?php

declare(strict_types=1);

namespace App\Post\Infrastructure\Doctrine\Document;

use DateTimeImmutable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(collection: 'posts')]
#[MongoDB\Index(keys: ['circleId' => 'asc', 'createdAt' => 'desc'])]
#[MongoDB\Index(keys: ['authorId' => 'asc', 'createdAt' => 'desc'])]
#[MongoDB\Index(keys: ['createdAt' => 'desc'])]
#[MongoDB\Index(keys: ['voteScore' => 'desc', 'createdAt' => 'desc'])]
#[MongoDB\Index(keys: ['deletedAt' => 'asc'])]
#[MongoDB\Index(keys: ['isPinned' => 'asc', 'createdAt' => 'desc'])]
class PostDocument
{
    #[MongoDB\Id(strategy: 'NONE', type: 'string')]
    private string $id;

    #[MongoDB\Field(type: 'string')]
    private string $circleId;

    #[MongoDB\Field(type: 'string')]
    private string $authorId;

    #[MongoDB\Field(type: 'string')]
    private string $title;

    #[MongoDB\Field(type: 'string')]
    private string $content;

    #[MongoDB\Field(type: 'collection')]
    private array $imageUrls;

    #[MongoDB\Field(type: 'int')]
    private int $voteScore;

    #[MongoDB\Field(type: 'int')]
    private int $commentCount;

    #[MongoDB\Field(type: 'string', nullable: true)]
    private ?string $aiSummary;

    #[MongoDB\Field(type: 'date_immutable')]
    private DateTimeImmutable $createdAt;

    #[MongoDB\Field(type: 'date_immutable', nullable: true)]
    private ?DateTimeImmutable $updatedAt;

    #[MongoDB\Field(type: 'date_immutable', nullable: true)]
    private ?DateTimeImmutable $deletedAt;

    #[MongoDB\Field(type: 'bool')]
    private bool $isPinned;

    public function __construct(
        string $id,
        string $circleId,
        string $authorId,
        string $title,
        string $content,
        array $imageUrls,
        int $voteScore,
        int $commentCount,
        DateTimeImmutable $createdAt,
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

    public function getId(): string
    {
        return $this->id;
    }

    public function getCircleId(): string
    {
        return $this->circleId;
    }

    public function getAuthorId(): string
    {
        return $this->authorId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getImageUrls(): array
    {
        return $this->imageUrls;
    }

    public function setImageUrls(array $imageUrls): void
    {
        $this->imageUrls = $imageUrls;
    }

    public function getVoteScore(): int
    {
        return $this->voteScore;
    }

    public function setVoteScore(int $voteScore): void
    {
        $this->voteScore = $voteScore;
    }

    public function getCommentCount(): int
    {
        return $this->commentCount;
    }

    public function setCommentCount(int $commentCount): void
    {
        $this->commentCount = $commentCount;
    }

    public function getAiSummary(): ?string
    {
        return $this->aiSummary;
    }

    public function setAiSummary(?string $aiSummary): void
    {
        $this->aiSummary = $aiSummary;
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

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTimeImmutable $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    public function isPinned(): bool
    {
        return $this->isPinned;
    }

    public function setIsPinned(bool $isPinned): void
    {
        $this->isPinned = $isPinned;
    }
}

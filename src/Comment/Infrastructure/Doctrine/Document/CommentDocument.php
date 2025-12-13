<?php

declare(strict_types=1);

namespace App\Comment\Infrastructure\Doctrine\Document;

use DateTimeImmutable;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(collection: 'comments')]
#[MongoDB\Index(keys: ['postId' => 'asc', 'createdAt' => 'asc'])]
#[MongoDB\Index(keys: ['authorId' => 'asc', 'createdAt' => 'desc'])]
#[MongoDB\Index(keys: ['deletedAt' => 'asc'])]
#[MongoDB\Index(keys: ['voteScore' => 'desc'])]
class CommentDocument
{
    #[MongoDB\Id(strategy: 'NONE', type: 'string')]
    private string $id;

    #[MongoDB\Field(type: 'string')]
    private string $postId;

    #[MongoDB\Field(type: 'string')]
    private string $authorId;

    #[MongoDB\Field(type: 'string')]
    private string $content;

    #[MongoDB\Field(type: 'int')]
    private int $voteScore;

    #[MongoDB\Field(type: 'date_immutable')]
    private DateTimeImmutable $createdAt;

    #[MongoDB\Field(type: 'date_immutable', nullable: true)]
    private ?DateTimeImmutable $updatedAt;

    #[MongoDB\Field(type: 'date_immutable', nullable: true)]
    private ?DateTimeImmutable $deletedAt;

    public function __construct(
        string $id,
        string $postId,
        string $authorId,
        string $content,
        int $voteScore,
        DateTimeImmutable $createdAt
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

    public function getId(): string
    {
        return $this->id;
    }

    public function getPostId(): string
    {
        return $this->postId;
    }

    public function getAuthorId(): string
    {
        return $this->authorId;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getVoteScore(): int
    {
        return $this->voteScore;
    }

    public function setVoteScore(int $voteScore): void
    {
        $this->voteScore = $voteScore;
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
}

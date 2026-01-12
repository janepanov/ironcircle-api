<?php

declare(strict_types=1);

namespace App\Post\Domain\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'posts')]
#[ODM\Index(keys: ['circleId' => 'asc', 'createdAt' => 'desc'])]
#[ODM\Index(keys: ['authorId' => 'asc'])]
#[ODM\Index(keys: ['voteScore' => 'desc'])]
class Post
{
    #[ODM\Id]
    private string $id;

    #[ODM\Field(type: 'string')]
    private string $circleId;

    #[ODM\Field(type: 'string')]
    private string $authorId;

    #[ODM\Field(type: 'string')]
    private string $title;

    #[ODM\Field(type: 'string')]
    private string $content;

    #[ODM\Field(type: 'int')]
    private int $voteScore = 0;

    #[ODM\Field(type: 'date')]
    private \DateTimeInterface $createdAt;

    #[ODM\Field(type: 'date')]
    private \DateTimeInterface $updatedAt;

    public function __construct(
        string $circleId,
        string $authorId,
        string $title,
        string $content
    ) {
        $this->circleId = $circleId;
        $this->authorId = $authorId;
        $this->title = $title;
        $this->content = $content;
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
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

    public function getContent(): string
    {
        return $this->content;
    }

    public function getVoteScore(): int
    {
        return $this->voteScore;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function update(string $title, string $content): void
    {
        $this->title = $title;
        $this->content = $content;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function incrementVoteScore(): void
    {
        $this->voteScore++;
    }

    public function decrementVoteScore(): void
    {
        $this->voteScore--;
    }

    public function isAuthor(string $userId): bool
    {
        return $this->authorId === $userId;
    }
}

<?php

declare(strict_types=1);

namespace App\Comment\Domain\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'comments')]
#[ODM\Index(keys: ['postId' => 'asc', 'createdAt' => 'asc'])]
#[ODM\Index(keys: ['authorId' => 'asc'])]
class Comment
{
    #[ODM\Id]
    private string $id;

    #[ODM\Field(type: 'string')]
    private string $postId;

    #[ODM\Field(type: 'string')]
    private string $authorId;

    #[ODM\Field(type: 'string')]
    private string $content;

    #[ODM\Field(type: 'date')]
    private \DateTimeInterface $createdAt;

    public function __construct(
        string $postId,
        string $authorId,
        string $content
    ) {
        $this->postId = $postId;
        $this->authorId = $authorId;
        $this->content = $content;
        $this->createdAt = new \DateTimeImmutable();
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

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function updateContent(string $content): void
    {
        $this->content = $content;
    }

    public function isAuthor(string $userId): bool
    {
        return $this->authorId === $userId;
    }
}

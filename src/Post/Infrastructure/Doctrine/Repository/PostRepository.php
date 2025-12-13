<?php

declare(strict_types=1);

namespace App\Post\Infrastructure\Doctrine\Repository;

use App\Circle\Domain\ValueObject\CircleId;
use App\Post\Domain\Model\Post;
use App\Post\Domain\Repository\PostRepositoryInterface;
use App\Post\Domain\ValueObject\PostContent;
use App\Post\Domain\ValueObject\PostId;
use App\Post\Domain\ValueObject\PostTitle;
use App\Post\Infrastructure\Doctrine\Document\PostDocument;
use App\User\Domain\ValueObject\UserId;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

final class PostRepository implements PostRepositoryInterface
{
    private DocumentRepository $repository;

    public function __construct(private readonly DocumentManager $documentManager)
    {
        $this->repository = $documentManager->getRepository(PostDocument::class);
    }

    public function save(Post $post): void
    {
        $document = $this->repository->find($post->id()->value());

        if ($document === null) {
            $document = $this->toDocument($post);
            $this->documentManager->persist($document);
        } else {
            $this->updateDocument($document, $post);
        }

        $this->documentManager->flush();
    }

    public function findById(PostId $id): ?Post
    {
        $document = $this->repository->find($id->value());

        return $document ? $this->toDomain($document) : null;
    }

    public function delete(Post $post): void
    {
        $document = $this->repository->find($post->id()->value());

        if ($document !== null) {
            $this->documentManager->remove($document);
            $this->documentManager->flush();
        }
    }

    public function findByCircleId(CircleId $circleId, int $limit = 20, int $offset = 0): array
    {
        $documents = $this->repository->findBy(
            [
                'circleId' => $circleId->value(),
                'deletedAt' => null
            ],
            ['createdAt' => 'DESC'],
            $limit,
            $offset
        );

        return array_map(fn($doc) => $this->toDomain($doc), $documents);
    }

    public function findByAuthorId(UserId $authorId, int $limit = 20, int $offset = 0): array
    {
        $documents = $this->repository->findBy(
            [
                'authorId' => $authorId->value(),
                'deletedAt' => null
            ],
            ['createdAt' => 'DESC'],
            $limit,
            $offset
        );

        return array_map(fn($doc) => $this->toDomain($doc), $documents);
    }

    public function findRecent(int $limit = 20, int $offset = 0): array
    {
        $documents = $this->repository->findBy(
            ['deletedAt' => null],
            ['createdAt' => 'DESC'],
            $limit,
            $offset
        );

        return array_map(fn($doc) => $this->toDomain($doc), $documents);
    }

    public function findHot(int $limit = 20, int $offset = 0): array
    {
        $documents = $this->repository->findBy(
            ['deletedAt' => null],
            ['voteScore' => 'DESC', 'createdAt' => 'DESC'],
            $limit,
            $offset
        );

        return array_map(fn($doc) => $this->toDomain($doc), $documents);
    }

    private function toDocument(Post $post): PostDocument
    {
        return new PostDocument(
            $post->id()->value(),
            $post->circleId()->value(),
            $post->authorId()->value(),
            $post->title()->value(),
            $post->content()->value(),
            $post->imageUrls(),
            $post->voteScore(),
            $post->commentCount(),
            $post->createdAt(),
            $post->aiSummary(),
            $post->isPinned()
        );
    }

    private function updateDocument(PostDocument $document, Post $post): void
    {
        $document->setTitle($post->title()->value());
        $document->setContent($post->content()->value());
        $document->setImageUrls($post->imageUrls());
        $document->setVoteScore($post->voteScore());
        $document->setCommentCount($post->commentCount());
        $document->setAiSummary($post->aiSummary());
        $document->setUpdatedAt($post->updatedAt());
        $document->setDeletedAt($post->deletedAt());
        $document->setIsPinned($post->isPinned());
    }

    private function toDomain(PostDocument $document): Post
    {
        return Post::create(
            PostId::fromString($document->getId()),
            CircleId::fromString($document->getCircleId()),
            UserId::fromString($document->getAuthorId()),
            new PostTitle($document->getTitle()),
            new PostContent($document->getContent()),
            $document->getImageUrls()
        );
    }
}

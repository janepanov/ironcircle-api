<?php

declare(strict_types=1);

namespace App\Comment\Infrastructure\Doctrine\Repository;

use App\Comment\Domain\Model\Comment;
use App\Comment\Domain\Repository\CommentRepositoryInterface;
use App\Comment\Domain\ValueObject\CommentContent;
use App\Comment\Domain\ValueObject\CommentId;
use App\Comment\Infrastructure\Doctrine\Document\CommentDocument;
use App\Post\Domain\ValueObject\PostId;
use App\User\Domain\ValueObject\UserId;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

final class CommentRepository implements CommentRepositoryInterface
{
    private DocumentRepository $repository;

    public function __construct(private readonly DocumentManager $documentManager)
    {
        $this->repository = $documentManager->getRepository(CommentDocument::class);
    }

    public function save(Comment $comment): void
    {
        $document = $this->repository->find($comment->id()->value());

        if ($document === null) {
            $document = $this->toDocument($comment);
            $this->documentManager->persist($document);
        } else {
            $this->updateDocument($document, $comment);
        }

        $this->documentManager->flush();
    }

    public function findById(CommentId $id): ?Comment
    {
        $document = $this->repository->find($id->value());

        return $document ? $this->toDomain($document) : null;
    }

    public function delete(Comment $comment): void
    {
        $document = $this->repository->find($comment->id()->value());

        if ($document !== null) {
            $this->documentManager->remove($document);
            $this->documentManager->flush();
        }
    }

    public function findByPostId(PostId $postId, int $limit = 50, int $offset = 0): array
    {
        $documents = $this->repository->findBy(
            [
                'postId' => $postId->value(),
                'deletedAt' => null
            ],
            ['createdAt' => 'ASC'],
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

    public function countByPostId(PostId $postId): int
    {
        $qb = $this->documentManager->createQueryBuilder(CommentDocument::class);
        
        return $qb->field('postId')->equals($postId->value())
            ->field('deletedAt')->equals(null)
            ->count()
            ->getQuery()
            ->execute();
    }

    private function toDocument(Comment $comment): CommentDocument
    {
        return new CommentDocument(
            $comment->id()->value(),
            $comment->postId()->value(),
            $comment->authorId()->value(),
            $comment->content()->value(),
            $comment->voteScore(),
            $comment->createdAt()
        );
    }

    private function updateDocument(CommentDocument $document, Comment $comment): void
    {
        $document->setContent($comment->content()->value());
        $document->setVoteScore($comment->voteScore());
        $document->setUpdatedAt($comment->updatedAt());
        $document->setDeletedAt($comment->deletedAt());
    }

    private function toDomain(CommentDocument $document): Comment
    {
        return Comment::create(
            CommentId::fromString($document->getId()),
            PostId::fromString($document->getPostId()),
            UserId::fromString($document->getAuthorId()),
            new CommentContent($document->getContent())
        );
    }
}

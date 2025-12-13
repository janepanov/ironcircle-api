<?php

declare(strict_types=1);

namespace App\Circle\Infrastructure\Doctrine\Repository;

use App\Circle\Domain\Model\Circle;
use App\Circle\Domain\Repository\CircleRepositoryInterface;
use App\Circle\Domain\ValueObject\CircleDescription;
use App\Circle\Domain\ValueObject\CircleId;
use App\Circle\Domain\ValueObject\CircleName;
use App\Circle\Domain\ValueObject\CircleSlug;
use App\Circle\Infrastructure\Doctrine\Document\CircleDocument;
use App\User\Domain\ValueObject\UserId;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

final class CircleRepository implements CircleRepositoryInterface
{
    private DocumentRepository $repository;

    public function __construct(private readonly DocumentManager $documentManager)
    {
        $this->repository = $documentManager->getRepository(CircleDocument::class);
    }

    public function save(Circle $circle): void
    {
        $document = $this->repository->find($circle->id()->value());

        if ($document === null) {
            $document = $this->toDocument($circle);
            $this->documentManager->persist($document);
        } else {
            $this->updateDocument($document, $circle);
        }

        $this->documentManager->flush();
    }

    public function findById(CircleId $id): ?Circle
    {
        $document = $this->repository->find($id->value());

        return $document ? $this->toDomain($document) : null;
    }

    public function findBySlug(CircleSlug $slug): ?Circle
    {
        $document = $this->repository->findOneBy(['slug' => $slug->value()]);

        return $document ? $this->toDomain($document) : null;
    }

    public function existsBySlug(CircleSlug $slug): bool
    {
        return $this->repository->findOneBy(['slug' => $slug->value()]) !== null;
    }

    public function delete(Circle $circle): void
    {
        $document = $this->repository->find($circle->id()->value());

        if ($document !== null) {
            $this->documentManager->remove($document);
            $this->documentManager->flush();
        }
    }

    public function findAll(int $limit = 20, int $offset = 0): array
    {
        $documents = $this->repository->findBy(
            ['isActive' => true],
            ['createdAt' => 'DESC'],
            $limit,
            $offset
        );

        return array_map(fn($doc) => $this->toDomain($doc), $documents);
    }

    private function toDocument(Circle $circle): CircleDocument
    {
        return new CircleDocument(
            $circle->id()->value(),
            $circle->name()->value(),
            $circle->slug()->value(),
            $circle->description()->value(),
            $circle->creatorId()->value(),
            $circle->moderatorIds(),
            $circle->memberCount(),
            $circle->postCount(),
            $circle->createdAt(),
            $circle->isActive()
        );
    }

    private function updateDocument(CircleDocument $document, Circle $circle): void
    {
        $document->setName($circle->name()->value());
        $document->setDescription($circle->description()->value());
        $document->setModeratorIds($circle->moderatorIds());
        $document->setMemberCount($circle->memberCount());
        $document->setPostCount($circle->postCount());
        $document->setUpdatedAt($circle->updatedAt());
        $document->setIsActive($circle->isActive());
    }

    private function toDomain(CircleDocument $document): Circle
    {
        return Circle::create(
            CircleId::fromString($document->getId()),
            new CircleName($document->getName()),
            new CircleSlug($document->getSlug()),
            new CircleDescription($document->getDescription()),
            UserId::fromString($document->getCreatorId())
        );
    }
}

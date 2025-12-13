<?php

declare(strict_types=1);

namespace App\Vote\Infrastructure\Doctrine\Repository;

use App\User\Domain\ValueObject\UserId;
use App\Vote\Domain\Model\Vote;
use App\Vote\Domain\Repository\VoteRepositoryInterface;
use App\Vote\Domain\ValueObject\VotableType;
use App\Vote\Domain\ValueObject\VoteId;
use App\Vote\Domain\ValueObject\VoteType;
use App\Vote\Infrastructure\Doctrine\Document\VoteDocument;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

final class VoteRepository implements VoteRepositoryInterface
{
    private DocumentRepository $repository;

    public function __construct(private readonly DocumentManager $documentManager)
    {
        $this->repository = $documentManager->getRepository(VoteDocument::class);
    }

    public function save(Vote $vote): void
    {
        $document = $this->repository->find($vote->id()->value());

        if ($document === null) {
            $document = $this->toDocument($vote);
            $this->documentManager->persist($document);
        } else {
            $this->updateDocument($document, $vote);
        }

        $this->documentManager->flush();
    }

    public function findById(VoteId $id): ?Vote
    {
        $document = $this->repository->find($id->value());

        return $document ? $this->toDomain($document) : null;
    }

    public function findByUserAndVotable(
        UserId $userId,
        string $votableId,
        VotableType $votableType
    ): ?Vote {
        $document = $this->repository->findOneBy([
            'userId' => $userId->value(),
            'votableId' => $votableId,
            'votableType' => $votableType->value
        ]);

        return $document ? $this->toDomain($document) : null;
    }

    public function existsByUserAndVotable(
        UserId $userId,
        string $votableId,
        VotableType $votableType
    ): bool {
        return $this->repository->findOneBy([
            'userId' => $userId->value(),
            'votableId' => $votableId,
            'votableType' => $votableType->value
        ]) !== null;
    }

    public function delete(Vote $vote): void
    {
        $document = $this->repository->find($vote->id()->value());

        if ($document !== null) {
            $this->documentManager->remove($document);
            $this->documentManager->flush();
        }
    }

    public function countByVotable(string $votableId, VotableType $votableType): int
    {
        $qb = $this->documentManager->createQueryBuilder(VoteDocument::class);

        return $qb->field('votableId')->equals($votableId)
            ->field('votableType')->equals($votableType->value)
            ->count()
            ->getQuery()
            ->execute();
    }

    public function calculateScoreByVotable(string $votableId, VotableType $votableType): int
    {
        $qb = $this->documentManager->createAggregationBuilder(VoteDocument::class);

        $result = $qb
            ->match()
                ->field('votableId')->equals($votableId)
                ->field('votableType')->equals($votableType->value)
            ->group()
                ->field('id')->expression(null)
                ->field('totalScore')->sum('$voteType')
            ->execute();

        $data = iterator_to_array($result);

        return !empty($data) ? (int) $data[0]['totalScore'] : 0;
    }

    private function toDocument(Vote $vote): VoteDocument
    {
        return new VoteDocument(
            $vote->id()->value(),
            $vote->userId()->value(),
            $vote->votableId(),
            $vote->votableType()->value,
            $vote->voteType()->value,
            $vote->createdAt()
        );
    }

    private function updateDocument(VoteDocument $document, Vote $vote): void
    {
        $document->setVoteType($vote->voteType()->value);
        $document->setUpdatedAt($vote->updatedAt());
    }

    private function toDomain(VoteDocument $document): Vote
    {
        return Vote::create(
            VoteId::fromString($document->getId()),
            UserId::fromString($document->getUserId()),
            $document->getVotableId(),
            VotableType::from($document->getVotableType()),
            VoteType::from($document->getVoteType())
        );
    }
}

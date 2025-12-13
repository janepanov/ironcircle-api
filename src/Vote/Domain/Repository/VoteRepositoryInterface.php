<?php

declare(strict_types=1);

namespace App\Vote\Domain\Repository;

use App\User\Domain\ValueObject\UserId;
use App\Vote\Domain\Model\Vote;
use App\Vote\Domain\ValueObject\VotableType;
use App\Vote\Domain\ValueObject\VoteId;

interface VoteRepositoryInterface
{
    public function save(Vote $vote): void;

    public function findById(VoteId $id): ?Vote;

    public function findByUserAndVotable(
        UserId $userId,
        string $votableId,
        VotableType $votableType
    ): ?Vote;

    public function existsByUserAndVotable(
        UserId $userId,
        string $votableId,
        VotableType $votableType
    ): bool;

    public function delete(Vote $vote): void;

    public function countByVotable(string $votableId, VotableType $votableType): int;

    public function calculateScoreByVotable(string $votableId, VotableType $votableType): int;
}

<?php

declare(strict_types=1);

namespace App\Circle\Domain\Repository;

use App\Circle\Domain\Model\Circle;
use App\Circle\Domain\ValueObject\CircleId;
use App\Circle\Domain\ValueObject\CircleSlug;

interface CircleRepositoryInterface
{
    public function save(Circle $circle): void;

    public function findById(CircleId $id): ?Circle;

    public function findBySlug(CircleSlug $slug): ?Circle;

    public function existsBySlug(CircleSlug $slug): bool;

    public function delete(Circle $circle): void;

    /**
     * @return Circle[]
     */
    public function findAll(int $limit = 20, int $offset = 0): array;
}

<?php

declare(strict_types=1);

namespace App\Post\Domain\Repository;

use App\Circle\Domain\ValueObject\CircleId;
use App\Post\Domain\Model\Post;
use App\Post\Domain\ValueObject\PostId;
use App\User\Domain\ValueObject\UserId;

interface PostRepositoryInterface
{
    public function save(Post $post): void;

    public function findById(PostId $id): ?Post;

    public function delete(Post $post): void;

    /**
     * @return Post[]
     */
    public function findByCircleId(CircleId $circleId, int $limit = 20, int $offset = 0): array;

    /**
     * @return Post[]
     */
    public function findByAuthorId(UserId $authorId, int $limit = 20, int $offset = 0): array;

    /**
     * @return Post[]
     */
    public function findRecent(int $limit = 20, int $offset = 0): array;

    /**
     * @return Post[]
     */
    public function findHot(int $limit = 20, int $offset = 0): array;
}

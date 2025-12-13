<?php

declare(strict_types=1);

namespace App\Comment\Domain\Repository;

use App\Comment\Domain\Model\Comment;
use App\Comment\Domain\ValueObject\CommentId;
use App\Post\Domain\ValueObject\PostId;
use App\User\Domain\ValueObject\UserId;

interface CommentRepositoryInterface
{
    public function save(Comment $comment): void;

    public function findById(CommentId $id): ?Comment;

    public function delete(Comment $comment): void;

    /**
     * @return Comment[]
     */
    public function findByPostId(PostId $postId, int $limit = 50, int $offset = 0): array;

    /**
     * @return Comment[]
     */
    public function findByAuthorId(UserId $authorId, int $limit = 20, int $offset = 0): array;

    public function countByPostId(PostId $postId): int;
}

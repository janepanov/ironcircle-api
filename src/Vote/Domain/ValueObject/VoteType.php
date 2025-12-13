<?php

declare(strict_types=1);

namespace App\Vote\Domain\ValueObject;

enum VoteType: int
{
    case UPVOTE = 1;
    case DOWNVOTE = -1;

    public function isUpvote(): bool
    {
        return $this === self::UPVOTE;
    }

    public function isDownvote(): bool
    {
        return $this === self::DOWNVOTE;
    }

    public function value(): int
    {
        return $this->value;
    }
}

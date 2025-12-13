<?php

declare(strict_types=1);

namespace App\Vote\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class VoteAlreadyExistsException extends DomainException
{
    public static function forUser(string $userId, string $votableId): self
    {
        return new self(
            sprintf('User "%s" has already voted on resource "%s"', $userId, $votableId)
        );
    }
}

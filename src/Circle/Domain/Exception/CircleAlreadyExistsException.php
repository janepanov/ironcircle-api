<?php

declare(strict_types=1);

namespace App\Circle\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class CircleAlreadyExistsException extends DomainException
{
    public static function withSlug(string $slug): self
    {
        return new self(sprintf('Circle with slug "%s" already exists', $slug));
    }
}

<?php

declare(strict_types=1);

namespace App\Post\Domain\ValueObject;

use InvalidArgumentException;

final class PostTitle
{
    private const MIN_LENGTH = 5;
    private const MAX_LENGTH = 300;

    private string $value;

    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = trim($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private function validate(string $value): void
    {
        $title = trim($value);

        if (empty($title)) {
            throw new InvalidArgumentException('Post title cannot be empty');
        }

        if (strlen($title) < self::MIN_LENGTH) {
            throw new InvalidArgumentException(
                sprintf('Post title must be at least %d characters', self::MIN_LENGTH)
            );
        }

        if (strlen($title) > self::MAX_LENGTH) {
            throw new InvalidArgumentException(
                sprintf('Post title cannot exceed %d characters', self::MAX_LENGTH)
            );
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Comment\Domain\ValueObject;

use InvalidArgumentException;

final class CommentContent
{
    private const MIN_LENGTH = 1;
    private const MAX_LENGTH = 2000;

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
        $content = trim($value);

        if (empty($content)) {
            throw new InvalidArgumentException('Comment content cannot be empty');
        }

        if (strlen($content) < self::MIN_LENGTH) {
            throw new InvalidArgumentException(
                sprintf('Comment content must be at least %d character', self::MIN_LENGTH)
            );
        }

        if (strlen($content) > self::MAX_LENGTH) {
            throw new InvalidArgumentException(
                sprintf('Comment content cannot exceed %d characters', self::MAX_LENGTH)
            );
        }
    }
}

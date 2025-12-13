<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use InvalidArgumentException;

final class Username
{
    private const MIN_LENGTH = 3;
    private const MAX_LENGTH = 30;
    private const PATTERN = '/^[a-zA-Z0-9_-]+$/';

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
        $username = trim($value);

        if (empty($username)) {
            throw new InvalidArgumentException('Username cannot be empty');
        }

        if (strlen($username) < self::MIN_LENGTH) {
            throw new InvalidArgumentException(
                sprintf('Username must be at least %d characters', self::MIN_LENGTH)
            );
        }

        if (strlen($username) > self::MAX_LENGTH) {
            throw new InvalidArgumentException(
                sprintf('Username cannot exceed %d characters', self::MAX_LENGTH)
            );
        }

        if (!preg_match(self::PATTERN, $username)) {
            throw new InvalidArgumentException(
                'Username can only contain letters, numbers, hyphens and underscores'
            );
        }
    }
}

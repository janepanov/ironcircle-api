<?php

declare(strict_types=1);

namespace App\Circle\Domain\ValueObject;

use InvalidArgumentException;

final class CircleName
{
    private const MIN_LENGTH = 3;
    private const MAX_LENGTH = 50;

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
        $name = trim($value);

        if (empty($name)) {
            throw new InvalidArgumentException('Circle name cannot be empty');
        }

        if (strlen($name) < self::MIN_LENGTH) {
            throw new InvalidArgumentException(
                sprintf('Circle name must be at least %d characters', self::MIN_LENGTH)
            );
        }

        if (strlen($name) > self::MAX_LENGTH) {
            throw new InvalidArgumentException(
                sprintf('Circle name cannot exceed %d characters', self::MAX_LENGTH)
            );
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Circle\Domain\ValueObject;

use InvalidArgumentException;

final class CircleDescription
{
    private const MAX_LENGTH = 500;

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
        $description = trim($value);

        if (strlen($description) > self::MAX_LENGTH) {
            throw new InvalidArgumentException(
                sprintf('Circle description cannot exceed %d characters', self::MAX_LENGTH)
            );
        }
    }
}

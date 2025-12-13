<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use InvalidArgumentException;

abstract class EntityId
{
    private string $value;

    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    public static function generate(): static
    {
        return new static(self::generateUniqueId());
    }

    public static function fromString(string $value): static
    {
        return new static($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value && get_class($this) === get_class($other);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private function validate(string $value): void
    {
        if (empty($value)) {
            throw new InvalidArgumentException('Entity ID cannot be empty');
        }

        if (strlen($value) < 20 || strlen($value) > 30) {
            throw new InvalidArgumentException('Entity ID must be between 20 and 30 characters');
        }
    }

    private static function generateUniqueId(): string
    {
        return bin2hex(random_bytes(12));
    }
}

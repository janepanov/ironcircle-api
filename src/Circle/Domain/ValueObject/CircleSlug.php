<?php

declare(strict_types=1);

namespace App\Circle\Domain\ValueObject;

use InvalidArgumentException;

final class CircleSlug
{
    private const MIN_LENGTH = 3;
    private const MAX_LENGTH = 50;
    private const PATTERN = '/^[a-z0-9-]+$/';

    private string $value;

    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = strtolower(trim($value));
    }

    public static function fromName(string $name): self
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        $slug = trim($slug, '-');

        return new self($slug);
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
        $slug = trim($value);

        if (empty($slug)) {
            throw new InvalidArgumentException('Circle slug cannot be empty');
        }

        if (strlen($slug) < self::MIN_LENGTH) {
            throw new InvalidArgumentException(
                sprintf('Circle slug must be at least %d characters', self::MIN_LENGTH)
            );
        }

        if (strlen($slug) > self::MAX_LENGTH) {
            throw new InvalidArgumentException(
                sprintf('Circle slug cannot exceed %d characters', self::MAX_LENGTH)
            );
        }

        if (!preg_match(self::PATTERN, $slug)) {
            throw new InvalidArgumentException(
                'Circle slug can only contain lowercase letters, numbers and hyphens'
            );
        }
    }
}

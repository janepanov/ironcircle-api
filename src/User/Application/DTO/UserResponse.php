<?php

declare(strict_types=1);

namespace App\User\Application\DTO;

use DateTimeImmutable;

final readonly class UserResponse
{
    public function __construct(
        public string $id,
        public string $username,
        public string $email,
        public string $role,
        public bool $isActive,
        public DateTimeImmutable $createdAt
    ) {
    }
}

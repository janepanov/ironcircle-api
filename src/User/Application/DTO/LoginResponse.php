<?php

declare(strict_types=1);

namespace App\User\Application\DTO;

final readonly class LoginResponse
{
    public function __construct(
        public string $token,
        public UserResponse $user
    ) {
    }
}

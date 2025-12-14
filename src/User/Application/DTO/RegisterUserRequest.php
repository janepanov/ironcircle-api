<?php

declare(strict_types=1);

namespace App\User\Application\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class RegisterUserRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'Username is required')]
        #[Assert\Length(
            min: 3,
            max: 30,
            minMessage: 'Username must be at least {{ limit }} characters',
            maxMessage: 'Username cannot exceed {{ limit }} characters'
        )]
        #[Assert\Regex(
            pattern: '/^[a-zA-Z0-9_-]+$/',
            message: 'Username can only contain letters, numbers, hyphens and underscores'
        )]
        public readonly string $username,

        #[Assert\NotBlank(message: 'Email is required')]
        #[Assert\Email(message: 'Invalid email format')]
        #[Assert\Length(max: 255)]
        public readonly string $email,

        #[Assert\NotBlank(message: 'Password is required')]
        #[Assert\Length(
            min: 8,
            minMessage: 'Password must be at least {{ limit }} characters'
        )]
        #[Assert\Regex(
            pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/',
            message: 'Password must contain at least one uppercase letter, one lowercase letter, and one number'
        )]
        public readonly string $password
    ) {
    }
}

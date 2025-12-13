<?php

declare(strict_types=1);

namespace App\User\Domain\ValueObject;

use InvalidArgumentException;

enum UserRole: string
{
    case ADMIN = 'ROLE_ADMIN';
    case MODERATOR = 'ROLE_MODERATOR';
    case MEMBER = 'ROLE_MEMBER';
    case GUEST = 'ROLE_GUEST';

    public function hasHigherOrEqualPrivilege(self $role): bool
    {
        return $this->privilegeLevel() >= $role->privilegeLevel();
    }

    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }

    public function isModerator(): bool
    {
        return $this === self::MODERATOR;
    }

    public function canModerate(): bool
    {
        return $this->isAdmin() || $this->isModerator();
    }

    private function privilegeLevel(): int
    {
        return match ($this) {
            self::ADMIN => 4,
            self::MODERATOR => 3,
            self::MEMBER => 2,
            self::GUEST => 1,
        };
    }
}

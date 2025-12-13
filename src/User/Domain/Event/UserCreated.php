<?php

declare(strict_types=1);

namespace App\User\Domain\Event;

use App\Shared\Domain\Event\DomainEvent;
use App\User\Domain\ValueObject\UserId;

final class UserCreated extends DomainEvent
{
    public function __construct(
        private readonly UserId $userId,
        private readonly string $username,
        private readonly string $email
    ) {
        parent::__construct();
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function eventName(): string
    {
        return 'user.created';
    }
}

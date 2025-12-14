<?php

declare(strict_types=1);

namespace App\User\Infrastructure\EventSubscriber;

use App\User\Domain\Event\UserCreated;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class UserCreatedSubscriber
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(UserCreated $event): void
    {
        $this->logger->info('User created', [
            'userId' => $event->userId()->value(),
            'username' => $event->username(),
            'email' => $event->email(),
            'occurredOn' => $event->occurredOn()->format('Y-m-d H:i:s')
        ]);
    }
}

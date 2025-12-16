<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\EventSubscriber;

use App\Shared\Domain\Event\DomainEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class DomainEventSubscriber
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(DomainEvent $event): void
    {
        $eventClass = get_class($event);
        $eventName = $event->eventName();
        $occurredOn = $event->occurredOn()->format('Y-m-d H:i:s');

        $context = [
            'event_class' => $eventClass,
            'event_name' => $eventName,
            'occurred_on' => $occurredOn,
            'event_data' => $this->extractEventData($event)
        ];

        $this->logger->info(
            sprintf('Domain event dispatched: %s', $eventName),
            $context
        );
    }

    private function extractEventData(DomainEvent $event): array
    {
        $reflection = new \ReflectionClass($event);
        $data = [];

        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->getNumberOfParameters() === 0 
                && $method->getName() !== 'eventName' 
                && $method->getName() !== 'occurredOn'
                && !str_starts_with($method->getName(), '__')
            ) {
                $methodName = $method->getName();
                $value = $method->invoke($event);
                
                $data[$methodName] = $this->normalizeValue($value);
            }
        }

        return $data;
    }

    private function normalizeValue(mixed $value): mixed
    {
        if (is_object($value)) {
            if (method_exists($value, 'value')) {
                return $value->value();
            }
            if (method_exists($value, '__toString')) {
                return (string) $value;
            }
            return get_class($value);
        }

        return $value;
    }
}

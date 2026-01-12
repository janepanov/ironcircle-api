<?php

declare(strict_types=1);

namespace App\AiSummary\Domain\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'ai_summaries')]
#[ODM\UniqueIndex(keys: ['targetType' => 'asc', 'targetId' => 'asc'])]
#[ODM\Index(keys: ['expiresAt' => 'asc'])]
class AiSummary
{
    #[ODM\Id]
    private string $id;

    #[ODM\Field(type: 'string')]
    private string $targetType;

    #[ODM\Field(type: 'string')]
    private string $targetId;

    #[ODM\Field(type: 'string')]
    private string $summary;

    #[ODM\Field(type: 'date')]
    private \DateTimeInterface $createdAt;

    #[ODM\Field(type: 'date')]
    private \DateTimeInterface $expiresAt;

    public function __construct(
        string $targetType,
        string $targetId,
        string $summary,
        int $ttlInHours = 24
    ) {
        $this->targetType = $targetType;
        $this->targetId = $targetId;
        $this->summary = $summary;
        $this->createdAt = new \DateTimeImmutable();
        $this->expiresAt = (new \DateTimeImmutable())->modify("+{$ttlInHours} hours");
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTargetType(): string
    {
        return $this->targetType;
    }

    public function getTargetId(): string
    {
        return $this->targetId;
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getExpiresAt(): \DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function isExpired(): bool
    {
        return $this->expiresAt < new \DateTimeImmutable();
    }

    public function refresh(string $newSummary, int $ttlInHours = 24): void
    {
        $this->summary = $newSummary;
        $this->createdAt = new \DateTimeImmutable();
        $this->expiresAt = (new \DateTimeImmutable())->modify("+{$ttlInHours} hours");
    }
}

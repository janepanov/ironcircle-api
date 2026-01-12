<?php

declare(strict_types=1);

namespace App\Moderation\Domain\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'flags')]
#[ODM\Index(keys: ['status' => 'asc', 'createdAt' => 'desc'])]
#[ODM\Index(keys: ['targetType' => 'asc', 'targetId' => 'asc'])]
#[ODM\Index(keys: ['reporterId' => 'asc'])]
class Flag
{
    #[ODM\Id]
    private string $id;

    #[ODM\Field(type: 'string')]
    private string $reporterId;

    #[ODM\Field(type: 'string')]
    private string $targetType;

    #[ODM\Field(type: 'string')]
    private string $targetId;

    #[ODM\Field(type: 'string')]
    private string $reason;

    #[ODM\Field(type: 'string')]
    private string $status;

    #[ODM\Field(type: 'date')]
    private \DateTimeInterface $createdAt;

    #[ODM\Field(type: 'date', nullable: true)]
    private ?\DateTimeInterface $resolvedAt = null;

    #[ODM\Field(type: 'string', nullable: true)]
    private ?string $resolvedBy = null;

    public function __construct(
        string $reporterId,
        string $targetType,
        string $targetId,
        string $reason
    ) {
        $this->reporterId = $reporterId;
        $this->targetType = $targetType;
        $this->targetId = $targetId;
        $this->reason = $reason;
        $this->status = 'pending';
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getReporterId(): string
    {
        return $this->reporterId;
    }

    public function getTargetType(): string
    {
        return $this->targetType;
    }

    public function getTargetId(): string
    {
        return $this->targetId;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getResolvedAt(): ?\DateTimeInterface
    {
        return $this->resolvedAt;
    }

    public function getResolvedBy(): ?string
    {
        return $this->resolvedBy;
    }

    public function resolve(string $moderatorId): void
    {
        $this->status = 'resolved';
        $this->resolvedAt = new \DateTimeImmutable();
        $this->resolvedBy = $moderatorId;
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isResolved(): bool
    {
        return $this->status === 'resolved';
    }
}

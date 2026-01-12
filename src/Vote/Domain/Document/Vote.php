<?php

declare(strict_types=1);

namespace App\Vote\Domain\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'votes')]
#[ODM\UniqueIndex(keys: ['userId' => 'asc', 'targetType' => 'asc', 'targetId' => 'asc'])]
#[ODM\Index(keys: ['targetType' => 'asc', 'targetId' => 'asc'])]
class Vote
{
    #[ODM\Id]
    private string $id;

    #[ODM\Field(type: 'string')]
    private string $userId;

    #[ODM\Field(type: 'string')]
    private string $targetType;

    #[ODM\Field(type: 'string')]
    private string $targetId;

    #[ODM\Field(type: 'int')]
    private int $value;

    public function __construct(
        string $userId,
        string $targetType,
        string $targetId,
        int $value
    ) {
        $this->userId = $userId;
        $this->targetType = $targetType;
        $this->targetId = $targetId;
        $this->setValue($value);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getTargetType(): string
    {
        return $this->targetType;
    }

    public function getTargetId(): string
    {
        return $this->targetId;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function setValue(int $value): void
    {
        if (!in_array($value, [1, -1], true)) {
            throw new \InvalidArgumentException('Vote value must be either 1 or -1');
        }
        $this->value = $value;
    }

    public function isUpvote(): bool
    {
        return $this->value === 1;
    }

    public function isDownvote(): bool
    {
        return $this->value === -1;
    }

    public function toggleValue(): void
    {
        $this->value = $this->value === 1 ? -1 : 1;
    }
}

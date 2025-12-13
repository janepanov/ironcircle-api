<?php

declare(strict_types=1);

namespace App\Circle\Domain\Model;

use App\Circle\Domain\ValueObject\CircleDescription;
use App\Circle\Domain\ValueObject\CircleId;
use App\Circle\Domain\ValueObject\CircleName;
use App\Circle\Domain\ValueObject\CircleSlug;
use App\User\Domain\ValueObject\UserId;
use DateTimeImmutable;
use InvalidArgumentException;

final class Circle
{
    private CircleId $id;
    private CircleName $name;
    private CircleSlug $slug;
    private CircleDescription $description;
    private UserId $creatorId;
    private array $moderatorIds;
    private int $memberCount;
    private int $postCount;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $updatedAt;
    private bool $isActive;

    private function __construct(
        CircleId $id,
        CircleName $name,
        CircleSlug $slug,
        CircleDescription $description,
        UserId $creatorId,
        DateTimeImmutable $createdAt,
        array $moderatorIds = [],
        int $memberCount = 1,
        int $postCount = 0,
        bool $isActive = true
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
        $this->description = $description;
        $this->creatorId = $creatorId;
        $this->moderatorIds = $moderatorIds;
        $this->memberCount = $memberCount;
        $this->postCount = $postCount;
        $this->createdAt = $createdAt;
        $this->updatedAt = null;
        $this->isActive = $isActive;
    }

    public static function create(
        CircleId $id,
        CircleName $name,
        CircleSlug $slug,
        CircleDescription $description,
        UserId $creatorId
    ): self {
        return new self(
            $id,
            $name,
            $slug,
            $description,
            $creatorId,
            new DateTimeImmutable(),
            [$creatorId->value()],
            1,
            0,
            true
        );
    }

    public function updateDetails(CircleName $name, CircleDescription $description): void
    {
        $this->name = $name;
        $this->description = $description;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function addModerator(UserId $userId): void
    {
        if (in_array($userId->value(), $this->moderatorIds, true)) {
            throw new InvalidArgumentException('User is already a moderator');
        }

        $this->moderatorIds[] = $userId->value();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function removeModerator(UserId $userId): void
    {
        if ($userId->equals($this->creatorId)) {
            throw new InvalidArgumentException('Cannot remove circle creator as moderator');
        }

        $key = array_search($userId->value(), $this->moderatorIds, true);
        if ($key === false) {
            throw new InvalidArgumentException('User is not a moderator');
        }

        unset($this->moderatorIds[$key]);
        $this->moderatorIds = array_values($this->moderatorIds);
        $this->updatedAt = new DateTimeImmutable();
    }

    public function incrementMemberCount(): void
    {
        $this->memberCount++;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function decrementMemberCount(): void
    {
        if ($this->memberCount > 0) {
            $this->memberCount--;
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    public function incrementPostCount(): void
    {
        $this->postCount++;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function decrementPostCount(): void
    {
        if ($this->postCount > 0) {
            $this->postCount--;
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    public function deactivate(): void
    {
        $this->isActive = false;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function activate(): void
    {
        $this->isActive = true;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function isModerator(UserId $userId): bool
    {
        return in_array($userId->value(), $this->moderatorIds, true);
    }

    public function id(): CircleId
    {
        return $this->id;
    }

    public function name(): CircleName
    {
        return $this->name;
    }

    public function slug(): CircleSlug
    {
        return $this->slug;
    }

    public function description(): CircleDescription
    {
        return $this->description;
    }

    public function creatorId(): UserId
    {
        return $this->creatorId;
    }

    public function moderatorIds(): array
    {
        return $this->moderatorIds;
    }

    public function memberCount(): int
    {
        return $this->memberCount;
    }

    public function postCount(): int
    {
        return $this->postCount;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}

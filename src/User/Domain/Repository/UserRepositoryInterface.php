<?php

declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\Username;
use App\User\Domain\Model\User;
use App\User\Domain\ValueObject\UserId;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function findById(UserId $id): ?User;

    public function findByEmail(Email $email): ?User;

    public function findByUsername(Username $username): ?User;

    public function existsByEmail(Email $email): bool;

    public function existsByUsername(Username $username): bool;

    public function delete(User $user): void;
}

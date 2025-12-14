<?php

declare(strict_types=1);

namespace App\User\Application\Transformer;

use App\User\Application\DTO\UserResponse;
use League\Fractal\TransformerAbstract;

final class UserTransformer extends TransformerAbstract
{
    public function transform(UserResponse $user): array
    {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role,
            'isActive' => $user->isActive,
            'createdAt' => $user->createdAt->format('c'),
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Vote\Domain\ValueObject;

enum VotableType: string
{
    case POST = 'post';
    case COMMENT = 'comment';
}

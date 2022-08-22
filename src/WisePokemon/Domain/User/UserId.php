<?php

declare(strict_types=1);

namespace App\WisePokemon\Domain\User;

use App\WisePokemon\Infrastructure\Persistence\Uuid\Identifier;

class UserId extends Identifier
{
    public static function getPrefix(): string
    {
        return 'UserId';
    }
}

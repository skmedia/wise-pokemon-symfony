<?php

declare(strict_types=1);

namespace App\WisePokemon\Domain\Team;

use App\WisePokemon\Infrastructure\Persistence\Uuid\Identifier;

class TeamId extends Identifier
{
    public static function getPrefix(): string
    {
        return 'TeamId';
    }
}

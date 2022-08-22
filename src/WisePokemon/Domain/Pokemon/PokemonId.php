<?php

declare(strict_types=1);

namespace App\WisePokemon\Domain\Pokemon;

use App\WisePokemon\Infrastructure\Persistence\Uuid\Identifier;

class PokemonId extends Identifier
{
    public static function getPrefix(): string
    {
        return 'PokemonId';
    }
}

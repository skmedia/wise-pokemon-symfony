<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Read\Pokemon\GetPokemonListingV2;

use App\WisePokemon\Domain\Pokemon\PokemonId;
use App\WisePokemon\Domain\Pokemon\PokemonName;

class GetPokemonListingItem implements \JsonSerializable
{
    public function __construct(
        private readonly PokemonId $id,
        private readonly PokemonName $name,
        private readonly array $types,
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'types' => $this->types,
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Read\Pokemon\GetPokemonById;

use App\WisePokemon\Domain\Pokemon\PokemonId;
use App\WisePokemon\Domain\Pokemon\PokemonImportId;
use App\WisePokemon\Domain\Pokemon\PokemonName;

class GetPokemonByIdItem implements \JsonSerializable
{
    public function __construct(
        private readonly PokemonId $pokemonId,
        private readonly PokemonName $name,
        private readonly PokemonImportId $importId,
        private readonly array $types,
    ) {
    }


    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->pokemonId,
            'name' => $this->name,
            'importId' => $this->importId,
            'types' => $this->types,
        ];
    }
}

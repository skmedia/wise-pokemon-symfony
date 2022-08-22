<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Read\Team\GetTeamListing;

use App\WisePokemon\Domain\Pokemon\Pokemon;
use App\WisePokemon\Domain\Team\TeamId;
use App\WisePokemon\Domain\Team\TeamName;

class GetTeamListingItem implements \JsonSerializable
{
    public function __construct(
        private readonly TeamId $id,
        private readonly TeamName $name,
        private readonly array $pokemons,
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'pokemons' => array_map(static fn (Pokemon $pokemon) => $pokemon->getId(), $this->pokemons),
        ];
    }
}

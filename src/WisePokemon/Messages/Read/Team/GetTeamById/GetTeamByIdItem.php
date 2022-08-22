<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Read\Team\GetTeamById;

use App\WisePokemon\Domain\Pokemon\Pokemon;
use App\WisePokemon\Domain\Team\TeamId;
use App\WisePokemon\Domain\Team\TeamName;

class GetTeamByIdItem implements \JsonSerializable
{
    public function __construct(
        private readonly TeamId $teamId,
        private readonly TeamName $name,
        private readonly array $pokemons,
    ) {
    }


    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->teamId,
            'name' => $this->name,
            'pokemons' => array_map(
                static fn (Pokemon $pokemon) => ['id' => $pokemon->getId(), 'name' => $pokemon->getName()],
                $this->pokemons
            )
        ];
    }
}

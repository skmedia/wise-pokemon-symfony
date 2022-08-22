<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Write\Team\AssignPokemonsToTeam;

use App\WisePokemon\Domain\Pokemon\PokemonId;
use App\WisePokemon\Domain\Pokemon\PokemonRepository;
use App\WisePokemon\Domain\Team\TeamRepository;

class AssignPokemonsToTeamHandler
{
    public function __construct(
        private readonly PokemonRepository $pokemonRepository,
        private readonly TeamRepository $teamRepository
    ) {
    }

    public function __invoke(AssignPokemonsToTeam $message): void
    {
        $team = $this->teamRepository->getOneById($message->getId());

        $pokemons = array_map(
            fn (PokemonId $pokemonId) => $this->pokemonRepository->getOneById($pokemonId),
            $message->getPokemons()
        );

        $team->assignPokemons($pokemons);
    }
}

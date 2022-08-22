<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Write\Pokemon\AddPokemon;

use App\WisePokemon\Domain\Pokemon\Pokemon;
use App\WisePokemon\Domain\Pokemon\PokemonRepository;

class AddPokemonHandler
{
    public function __construct(
        private readonly PokemonRepository $pokemonRepository
    ) {
    }

    public function __invoke(AddPokemon $message)
    {
        $pokemon = Pokemon::create(
            $message->getImportId(),
            $message->getName(),
            $message->getTypes(),
        );
        $this->pokemonRepository->add($pokemon);
    }
}

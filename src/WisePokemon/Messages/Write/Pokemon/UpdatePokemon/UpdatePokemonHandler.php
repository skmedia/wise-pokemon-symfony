<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Write\Pokemon\UpdatePokemon;

use App\WisePokemon\Domain\Pokemon\PokemonRepository;

class UpdatePokemonHandler
{
    public function __construct(
        private readonly PokemonRepository $pokemonRepository
    ) {
    }

    public function __invoke(UpdatePokemon $message): void
    {
        $pokemon = $this->pokemonRepository->getOneById($message->getId());
        $pokemon->update($message->getName(), $message->getTypes());
    }
}

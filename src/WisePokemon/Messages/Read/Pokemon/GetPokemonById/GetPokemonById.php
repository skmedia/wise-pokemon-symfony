<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Read\Pokemon\GetPokemonById;

use App\WisePokemon\Domain\Pokemon\Pokemon;
use App\WisePokemon\Domain\Pokemon\PokemonId;
use App\WisePokemon\Infrastructure\Message\Message;
use Symfony\Component\Validator\Constraints as Assert;
use App\WisePokemon\Infrastructure\Validation as AppAssert;

class GetPokemonById implements Message
{
    public function __construct(
        #[AppAssert\EntityExists(entityClass: Pokemon::class, message: "Pokemon does not exist")]
        #[Assert\NotBlank(message: "id is required")]
        private readonly string $pokemonId
    ) {
    }

    public function getPokemonId(): PokemonId
    {
        return new PokemonId($this->pokemonId);
    }
}

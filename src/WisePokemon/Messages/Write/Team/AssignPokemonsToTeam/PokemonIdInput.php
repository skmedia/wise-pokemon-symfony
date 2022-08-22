<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Write\Team\AssignPokemonsToTeam;

use App\WisePokemon\Domain\Pokemon\Pokemon;
use App\WisePokemon\Domain\Pokemon\PokemonId;
use App\WisePokemon\Infrastructure\Validation as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

class PokemonIdInput
{
    public function __construct(
        #[AppAssert\EntityExists(entityClass: Pokemon::class, message: "Pokemon does not exist")]
        #[Assert\NotBlank(message: "id is required")]
        private readonly string $id,
    ) {
    }

    public function getId(): PokemonId
    {
        return PokemonId::fromString($this->id);
    }
}

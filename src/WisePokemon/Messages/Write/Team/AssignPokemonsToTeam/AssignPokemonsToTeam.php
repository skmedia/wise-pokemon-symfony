<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Write\Team\AssignPokemonsToTeam;

use App\WisePokemon\Domain\Pokemon\PokemonId;
use App\WisePokemon\Domain\Team\TeamId;
use App\WisePokemon\Infrastructure\Message\Message;
use Symfony\Component\Validator\Constraints as Assert;

class AssignPokemonsToTeam implements Message
{
    public function __construct(
        #[Assert\NotBlank(message: "id is required")]
        private readonly string $id,
        #[Assert\Valid]
        private readonly array $pokemons,
    ) {
    }

    public function getId(): TeamId
    {
        return TeamId::fromString($this->id);
    }

    /**
     * @return PokemonId[]
     */
    public function getPokemons(): array
    {
        return array_map(static fn (string $id) => PokemonId::fromString($id), $this->pokemons);
    }
}

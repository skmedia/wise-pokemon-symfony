<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Write\Team\AssignPokemonsToTeam;

use App\WisePokemon\Domain\Team\Team;
use App\WisePokemon\Domain\Team\TeamId;
use App\WisePokemon\Infrastructure\Message\Message;
use Symfony\Component\Validator\Constraints as Assert;
use App\WisePokemon\Infrastructure\Validation as AppAssert;

class AssignPokemonsToTeam implements Message
{
    #[Assert\Valid]
    #[Assert\Count(max: 6, maxMessage: "max 6 pokemons allowed in team")]
    private readonly array $pokemonIds;

    public function __construct(
        #[AppAssert\EntityExists(entityClass: Team::class, message: "Team does not exist")]
        #[Assert\NotBlank(message: "id is required")]
        private readonly string $id,
        private readonly array $pokemons,
    ) {
        $this->pokemonIds = array_map(
            static fn (string $id) => new PokemonIdInput($id),
            $this->pokemons
        );
    }

    public function getId(): TeamId
    {
        return TeamId::fromString($this->id);
    }

    /**
     * @return PokemonIdInput[]
     */
    public function getPokemons(): array
    {
        return $this->pokemonIds;
    }
}

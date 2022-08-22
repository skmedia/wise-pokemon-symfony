<?php

declare(strict_types=1);

namespace App\Tests\WisePokemon\Messages\Write\Team\AssignPokemonsToTeam;

use App\Tests\DatabaseTestCase;
use App\WisePokemon\Domain\Team\Team;
use App\WisePokemon\Domain\Team\TeamId;
use App\WisePokemon\Domain\Team\TeamRepository;
use App\WisePokemon\Messages\Write\Team\AssignPokemonsToTeam\AssignPokemonsToTeam;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

class AssignPokemonsToTeamTest extends DatabaseTestCase
{
    /**
     * @test
     */
    public function it_should_assign_pokemons_to_a_team()
    {
        $this->databaseTool->loadFixtures(
            [
                AssignPokemonsToTeamFixtures::class,
            ],
            true
        );

        $this->dispatchWriteMessage(new AssignPokemonsToTeam(
            (string)TeamId::withPrefix('1'),
            ['PokemonId-1', 'PokemonId-2']
        ));

        $repo = $this->getContainer()->get(TeamRepository::class);
        $team = $repo->findOneById(TeamId::withPrefix('1'));

        $this->assertInstanceOf(Team::class, $team);
    }

    /**
     * @test
     */
    public function it_should_throw_an_error_when_the_team_does_not_exist()
    {
        $this->expectException(ValidationFailedException::class);

        $this->databaseTool->loadFixtures(
            [
                AssignPokemonsToTeamFixtures::class,
            ],
            true
        );

        $this->dispatchWriteMessage(new AssignPokemonsToTeam(
            (string)TeamId::withPrefix('2'),
            ['PokemonId-1', 'PokemonId-2']
        ));
    }
}

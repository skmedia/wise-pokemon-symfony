<?php

declare(strict_types=1);

namespace App\Tests\WisePokemon\Messages\Write\Team\AssignPokemonsToTeam;

use App\WisePokemon\Domain\Pokemon\Pokemon;
use App\WisePokemon\Domain\Pokemon\PokemonImportId;
use App\WisePokemon\Domain\Pokemon\PokemonName;
use App\WisePokemon\Domain\Team\Team;
use App\WisePokemon\Domain\Team\TeamId;
use App\WisePokemon\Domain\Team\TeamName;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class AssignPokemonsToTeamFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $team = new Team(TeamId::withPrefix('1'), new TeamName('team 1'));
        $manager->persist($team);

        $pokemons = [
            Pokemon::create(new PokemonImportId(1), new PokemonName('a'), ['normal']),
            Pokemon::create(new PokemonImportId(2), new PokemonName('b'), ['normal']),
            Pokemon::create(new PokemonImportId(3), new PokemonName('c'), ['normal']),
        ];

        foreach ($pokemons as $pokemon) {
            $manager->persist($pokemon);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return [
            'tests'
        ];
    }
}

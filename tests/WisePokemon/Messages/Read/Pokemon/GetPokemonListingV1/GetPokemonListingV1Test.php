<?php

declare(strict_types=1);

namespace App\Tests\WisePokemon\Messages\Read\Pokemon\GetPokemonListingV1;

use App\Tests\DatabaseTestCase;
use App\WisePokemon\Messages\Read\Pokemon\GetPokemonListingV1\GetPokemonListing;
use App\WisePokemon\Messages\Read\Pokemon\GetPokemonListingV1\GetPokemonListingItem;

class GetPokemonListingV1Test extends DatabaseTestCase
{
    /**
     * @test
     */
    public function it_should_list_all_pokemons()
    {
        $this->databaseTool->loadFixtures(
            [
                ListPokemonFixtures::class,
            ],
            true
        );

        /** @var GetPokemonListingItem[] $pokemons */
        $pokemons = $this->dispatchReadMessage(new GetPokemonListing('name-asc'));
        $this->assertEquals('a', (string)$pokemons[0]->jsonSerialize()['name']);

        /** @var GetPokemonListingItem[] $pokemons */
        $pokemons = $this->dispatchReadMessage(new GetPokemonListing('name-desc'));
        $this->assertEquals('c', (string)$pokemons[0]->jsonSerialize()['name']);

        /** @var GetPokemonListingItem[] $pokemons */
        $pokemons = $this->dispatchReadMessage(new GetPokemonListing('id-asc'));
        $this->assertEquals('a', (string)$pokemons[0]->jsonSerialize()['name']);

        /** @var GetPokemonListingItem[] $pokemons */
        $pokemons = $this->dispatchReadMessage(new GetPokemonListing('id-desc'));
        $this->assertEquals('c', (string)$pokemons[0]->jsonSerialize()['name']);
    }
}

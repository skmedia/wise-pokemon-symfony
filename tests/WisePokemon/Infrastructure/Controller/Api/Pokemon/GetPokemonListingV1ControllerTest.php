<?php

declare(strict_types=1);

namespace App\Tests\WisePokemon\Infrastructure\Controller\Api\Pokemon;

use App\Tests\DatabaseTestCase;

class GetPokemonListingV1ControllerTest extends DatabaseTestCase
{
    /**
     * @test
     */
    public function pokemon_listing_v1()
    {
        static::$client->request('GET', '/api/v1/pokemons', [
            'sort' => 'id-asc'
        ]);

        $this->assertResponseIsSuccessful();
    }
}

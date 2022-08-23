<?php

declare(strict_types=1);

namespace App\Tests\WisePokemon\Infrastructure\Controller\Api\Pokemon;

use App\Tests\DatabaseTestCase;

class GetPokemonListingV2ControllerTest extends DatabaseTestCase
{
    /**
     * @test
     */
    public function pokemon_listing_v2()
    {
        static::$client->request('GET', '/api/v2/pokemons', [
            'sort' => 'id-asc',
            'offset' => 0,
            'limit' => 10,
        ]);

        $this->assertResponseIsSuccessful();
    }
}

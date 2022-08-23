<?php

declare(strict_types=1);

namespace App\Tests\WisePokemon\Infrastructure\Controller\Api\Pokemon;

use App\Tests\DatabaseTestCase;

class SearchPokemonsControllerTest extends DatabaseTestCase
{
    /**
     * @test
     */
    public function search_pokemons()
    {
        static::$client->request('GET', '/api/v1/search', [
            'query' => 'pikachu'
        ]);

        $this->assertResponseIsSuccessful();
    }
}

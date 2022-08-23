<?php

declare(strict_types=1);

namespace App\Tests\WisePokemon\Infrastructure\Controller\Api\Pokemon;

use App\Tests\DatabaseTestCase;
use Symfony\Component\HttpFoundation\Response;

class GetPokemonByIdControllerTest extends DatabaseTestCase
{
    /**
     * @test
     */
    public function get_pokemon_by_id()
    {
        static::$client->request('GET', '/api/v1/pokemons/PokemonId-2');

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}

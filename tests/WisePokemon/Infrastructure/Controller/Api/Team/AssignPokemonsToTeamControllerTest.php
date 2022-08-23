<?php

declare(strict_types=1);

namespace App\Tests\WisePokemon\Infrastructure\Controller\Api\Team;

use App\Tests\DatabaseTestCase;
use Symfony\Component\HttpFoundation\Response;

class AssignPokemonsToTeamControllerTest extends DatabaseTestCase
{
    /**
     * @test
     */
    public function test_token()
    {
        static::$client->request('POST', '/api/v1/teams/Team-2');

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     */
    public function assign_pokemons_to_team()
    {
        $payload = \json_encode([
            'pokemons' => ['PokemonId-1']
        ]);
        static::$client->request('POST', '/api/v1/teams/Team-2', [], [], ['HTTP_Authorization' => "Bearer token"], $payload);

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}

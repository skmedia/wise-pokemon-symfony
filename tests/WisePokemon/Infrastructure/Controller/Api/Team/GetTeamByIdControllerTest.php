<?php

declare(strict_types=1);

namespace App\Tests\WisePokemon\Infrastructure\Controller\Api\Team;

use App\Tests\DatabaseTestCase;
use Symfony\Component\HttpFoundation\Response;

class GetTeamByIdControllerTest extends DatabaseTestCase
{
    /**
     * @test
     */
    public function get_team_by_id()
    {
        static::$client->request('GET', '/api/v1/teams/Team-2', [], [], ['HTTP_Authorization' => "Bearer token"]);

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}

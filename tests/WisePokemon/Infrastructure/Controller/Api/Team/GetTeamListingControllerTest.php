<?php

declare(strict_types=1);

namespace App\Tests\WisePokemon\Infrastructure\Controller\Api\Team;

use App\Tests\DatabaseTestCase;

class GetTeamListingControllerTest extends DatabaseTestCase
{
    /**
     * @test
     */
    public function team_listing()
    {
        static::$client->request('GET', '/api/v1/teams', [], [], ['HTTP_Authorization' => "Bearer token"]);

        $this->assertResponseIsSuccessful();
    }
}

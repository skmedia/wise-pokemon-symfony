<?php

declare(strict_types=1);

namespace App\Tests\WisePokemon\Messages\Read\Team\GetTeamListing;

use App\Tests\DatabaseTestCase;
use App\WisePokemon\Messages\Read\Team\GetTeamListing\GetTeamListing;
use App\WisePokemon\Messages\Read\Team\GetTeamListing\GetTeamListingItem;

class GetTeamListingTest extends DatabaseTestCase
{
    /**
     * @test
     */
    public function it_should_list_all_teams()
    {
        $this->databaseTool->loadFixtures(
            [
                GetTeamListingFixtures::class,
            ],
            true
        );

        /** @var GetTeamListingItem[] $pokemons */
        $teams = $this->dispatchReadMessage(new GetTeamListing());
        $this->assertCount(2, $teams);
    }
}

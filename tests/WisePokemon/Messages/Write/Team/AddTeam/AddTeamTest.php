<?php

declare(strict_types=1);

namespace App\Tests\WisePokemon\Messages\Write\Team\AddTeam;

use App\Tests\DatabaseTestCase;
use App\WisePokemon\Domain\Team\Team;
use App\WisePokemon\Domain\Team\TeamId;
use App\WisePokemon\Domain\Team\TeamRepository;
use App\WisePokemon\Messages\Write\Team\AddTeam\AddTeam;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

class AddTeamTest extends DatabaseTestCase
{
    /**
     * @test
     */
    public function it_should_add_a_team()
    {
        $this->dispatchWriteMessage(new AddTeam((string)TeamId::withPrefix('1'), 'name'));

        $repo = $this->getContainer()->get(TeamRepository::class);
        $team = $repo->findOneById(TeamId::withPrefix('1'));

        $this->assertInstanceOf(Team::class, $team);
    }

    /**
     * @test
     */
    public function it_should_throw_an_error_when_the_name_is_missing()
    {
        $this->expectException(ValidationFailedException::class);

        $this->dispatchWriteMessage(new AddTeam((string)TeamId::withPrefix('1'), ''));
    }
}

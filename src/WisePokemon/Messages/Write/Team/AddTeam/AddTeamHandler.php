<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Write\Team\AddTeam;

use App\WisePokemon\Domain\Team\Team;
use App\WisePokemon\Domain\Team\TeamRepository;

class AddTeamHandler
{
    public function __construct(
        private readonly TeamRepository $teamRepository
    ) {
    }

    public function __invoke(AddTeam $message)
    {
        $team = new Team($message->getId(), $message->getName());
        $this->teamRepository->add($team);
    }
}

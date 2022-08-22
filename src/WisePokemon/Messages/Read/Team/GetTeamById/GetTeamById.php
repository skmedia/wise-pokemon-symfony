<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Read\Team\GetTeamById;

use App\WisePokemon\Domain\Team\Team;
use App\WisePokemon\Domain\Team\TeamId;
use App\WisePokemon\Infrastructure\Message\Message;
use Symfony\Component\Validator\Constraints as Assert;
use App\WisePokemon\Infrastructure\Validation as AppAssert;

class GetTeamById implements Message
{
    public function __construct(
        #[Assert\NotBlank(message: "id is required")]
        #[AppAssert\EntityExists(entityClass: Team::class, message: "Team does not exist")]
        private readonly string $teamId
    ) {
    }

    public function getTeamId(): TeamId
    {
        return new TeamId($this->teamId);
    }
}

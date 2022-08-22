<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Read\Team\GetTeamById;

use App\WisePokemon\Domain\Team\TeamId;
use App\WisePokemon\Infrastructure\Message\Message;
use Symfony\Component\Validator\Constraints as Assert;

class GetTeamById implements Message
{
    public function __construct(
        #[Assert\NotBlank(message: "id is required")]
        private readonly string $teamId
    ) {
    }

    public function getTeamId(): TeamId
    {
        return new TeamId($this->teamId);
    }
}

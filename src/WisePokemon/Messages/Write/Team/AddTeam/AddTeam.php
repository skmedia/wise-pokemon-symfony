<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Write\Team\AddTeam;

use App\WisePokemon\Domain\Team\Team;
use App\WisePokemon\Domain\Team\TeamId;
use App\WisePokemon\Domain\Team\TeamName;
use App\WisePokemon\Infrastructure\Message\Message;
use Symfony\Component\Validator\Constraints as Assert;
use App\WisePokemon\Infrastructure\Validation as AppAssert;

#[AppAssert\UniqueValueInEntity(entityClass: Team::class, field: "id", message: "id must be unique", )]
#[AppAssert\UniqueValueInEntity(entityClass: Team::class, field: "name", message: "team name must be unique", )]
class AddTeam implements Message
{
    public function __construct(
        #[Assert\NotBlank(message: "id is required, a uuid will do")]
        private readonly string $id,
        #[Assert\NotBlank(message: "name is required")]
        private readonly string $name,
    ) {
    }

    public function getId(): TeamId
    {
        return TeamId::fromString($this->id);
    }

    public function getName(): TeamName
    {
        return new TeamName($this->name);
    }
}

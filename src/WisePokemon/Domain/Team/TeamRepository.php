<?php

declare(strict_types=1);

namespace App\WisePokemon\Domain\Team;

interface TeamRepository
{
    public function add(Team $team): void;
    public function findOneById(TeamId $id): ?Team;
    public function getOneById(TeamId $id): Team;
    public function save(Team $team): void;
}

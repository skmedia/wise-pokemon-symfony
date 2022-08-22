<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\Persistence\Repository;

use App\WisePokemon\Domain\Team\Team;
use App\WisePokemon\Domain\Team\TeamId;
use App\WisePokemon\Domain\Team\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class DoctrineTeamRepository implements TeamRepository
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function add(Team $team): void
    {
        $this->entityManager->persist($team);
    }

    public function save(Team $team): void
    {
        $this->entityManager->persist($team);
    }

    public function findOneById(TeamId $id): ?Team
    {
        return $this->entityManager->getRepository(Team::class)->find((string)$id);
    }

    public function getOneById(TeamId $id): Team
    {
        $team = $this->entityManager->getRepository(Team::class)->find((string)$id);

        if (!$team) {
            throw new EntityNotFoundException();
        }

        return $team;
    }
}

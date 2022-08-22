<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Read\Team\GetTeamListing;

use App\WisePokemon\Domain\Team\Team;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetTeamListingHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(GetTeamListing $message): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('team')
            ->from(Team::class, 'team')
            ->orderBy('team.name', 'asc');

        return \array_map(
            static fn (Team $team) => new GetTeamListingItem(
                $team->getId(),
                $team->getName(),
                $team->getPokemons(),
            ),
            $qb->getQuery()->getResult()
        );
    }
}

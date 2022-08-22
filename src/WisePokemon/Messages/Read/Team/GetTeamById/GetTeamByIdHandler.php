<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Read\Team\GetTeamById;

use App\WisePokemon\Domain\Team\Team;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetTeamByIdHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(GetTeamById $message): ?GetTeamByIdItem
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb
            ->select('team')
            ->from(Team::class, 'team')
            ->andWhere('team.id = :id')
            ->setParameter('id', $message->getTeamId());

        /** @var ?Team $team */
        $team = $qb->getQuery()->getOneOrNullResult();

        if ($team === null) {
            return null;
        }

        return new GetTeamByIdItem(
            $team->getId(),
            $team->getName(),
            $team->getPokemons(),
        );
    }
}

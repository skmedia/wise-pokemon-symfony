<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Read\Pokemon\GetPokemonById;

use App\WisePokemon\Domain\Pokemon\Pokemon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetPokemonByIdHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(GetPokemonById $message): ?GetPokemonByIdItem
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb
            ->select('pokemon')
            ->from(Pokemon::class, 'pokemon')
            ->andWhere('pokemon.id = :id')
            ->setParameter('id', $message->getPokemonId());

        /** @var ?Pokemon $pokemon */
        $pokemon = $qb->getQuery()->getOneOrNullResult();

        if ($pokemon === null) {
            return null;
        }

        return new GetPokemonByIdItem(
            $pokemon->getId(),
            $pokemon->getName(),
            $pokemon->getImportId(),
            $pokemon->getTypes(),
        );
    }
}

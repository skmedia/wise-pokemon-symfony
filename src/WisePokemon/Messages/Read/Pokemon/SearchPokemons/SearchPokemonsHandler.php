<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Read\Pokemon\SearchPokemons;

use App\WisePokemon\Domain\Pokemon\Pokemon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SearchPokemonsHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(SearchPokemons $message): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('pokemon')
            ->from(Pokemon::class, 'pokemon')
            ->orderBy('pokemon.name', 'asc')
            ->andWhere('pokemon.name like :query')
            ->orWhere('pokemon.types like :query')
            ->setParameter('query', '%'.$message->getQuery().'%')
        ;

        return \array_map(
            static fn (Pokemon $pokemon) => new SearchPokemonsItem(
                $pokemon->getId(),
                $pokemon->getName(),
                $pokemon->getTypes()
            ),
            $qb->getQuery()->getResult()
        );
    }
}

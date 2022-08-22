<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Read\Pokemon\GetPokemonListingV1;

use App\WisePokemon\Domain\Pokemon\Pokemon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetPokemonListingHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(GetPokemonListing $message): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('pokemon')
            ->from(Pokemon::class, 'pokemon')
            ->orderBy('pokemon.'.$message->getSort()['field'], $message->getSort()['dir']);

        return \array_map(
            static fn (Pokemon $pokemon) => new GetPokemonListingItem(
                $pokemon->getId(),
                $pokemon->getName(),
                $pokemon->getTypes(),
            ),
            $qb->getQuery()->getResult()
        );
    }
}

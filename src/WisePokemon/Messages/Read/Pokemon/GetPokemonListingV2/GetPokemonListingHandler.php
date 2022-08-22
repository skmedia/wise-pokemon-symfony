<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Read\Pokemon\GetPokemonListingV2;

use App\WisePokemon\Domain\Pokemon\Pokemon;
use App\WisePokemon\Infrastructure\Api\Result\MetadataFactory;
use App\WisePokemon\Infrastructure\Api\Result\PaginatedResult;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetPokemonListingHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly MetadataFactory $metadataFactory,
    ) {
    }

    public function __invoke(GetPokemonListing $message): PaginatedResult
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('pokemon')
            ->from(Pokemon::class, 'pokemon')
            ->orderBy('pokemon.'.$message->getSort()['field'], $message->getSort()['dir']);

        $qb->setFirstResult($message->getOffset())
            ->setMaxResults($message->getLimit());

        $paginator = new Paginator($qb);

        $data = \array_map(
            static fn (Pokemon $pokemon) => new GetPokemonListingItem(
                $pokemon->getId(),
                $pokemon->getName(),
                $pokemon->getTypes(),
            ),
            (array)$paginator->getIterator()
        );

        return new PaginatedResult(
            $data,
            $this->metadataFactory->create(
                'pokemon_listing_v2',
                $message->jsonSerialize(),
                $paginator->count(),
                $message->getOffset(),
                $message->getLimit()
            )
        );
    }
}

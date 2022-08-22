<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\Persistence\Repository;

use App\WisePokemon\Domain\Pokemon\Pokemon;
use App\WisePokemon\Domain\Pokemon\PokemonId;
use App\WisePokemon\Domain\Pokemon\PokemonName;
use App\WisePokemon\Domain\Pokemon\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class DoctrinePokemonRepository implements PokemonRepository
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function add(Pokemon $pokemon): void
    {
        $this->entityManager->persist($pokemon);
    }

    public function save(Pokemon $pokemon): void
    {
        $this->entityManager->persist($pokemon);
    }

    public function findOneByName(PokemonName $name): ?Pokemon
    {
        return $this->entityManager->getRepository(Pokemon::class)->findOneBy(['name' => (string)$name]);
    }

    public function findOneById(PokemonId $id): ?Pokemon
    {
        return $this->entityManager->getRepository(Pokemon::class)->find((string)$id);
    }

    public function getOneById(PokemonId $id): Pokemon
    {
        $pokemon = $this->entityManager->getRepository(Pokemon::class)->find((string)$id);

        if (!$pokemon) {
            throw new EntityNotFoundException();
        }

        return $pokemon;
    }
}

<?php

declare(strict_types=1);

namespace App\WisePokemon\Domain\Team;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Team
{
    private readonly \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;
    private Collection $pokemons;

    public function __construct(
        private readonly TeamId $id,
        private TeamName $name,
    ) {
        $this->pokemons = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): TeamId
    {
        return $this->id;
    }

    public function getName(): TeamName
    {
        return $this->name;
    }

    public function getPokemons(): array
    {
        return $this->pokemons->toArray();
    }

    public function update(TeamName $name, array $pokemons = []): void
    {
        $this->name = $name;
        $this->pokemons = new ArrayCollection($pokemons);

        $this->updatedAt = new \DateTimeImmutable();
    }

    public function assignPokemons(array $pokemans): void
    {
        $this->pokemons = new ArrayCollection($pokemans);

        $this->updatedAt = new \DateTimeImmutable();
    }
}

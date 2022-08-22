<?php

declare(strict_types=1);

namespace App\WisePokemon\Domain\Pokemon;

interface PokemonRepository
{
    public function add(Pokemon $pokemon): void;
    public function findOneByName(PokemonName $name): ?Pokemon;
    public function findOneById(PokemonId $id): ?Pokemon;
    public function getOneById(PokemonId $id): Pokemon;
    public function save(Pokemon $pokemon): void;
}

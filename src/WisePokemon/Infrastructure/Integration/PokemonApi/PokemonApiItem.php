<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\Integration\PokemonApi;

class PokemonApiItem
{
    public function __construct(
        private int $id,
        private string $name,
        private array $types,
    ) {
    }

    public static function create(array $item): self
    {
        $types = array_map(static fn (array $type): string => $type['type']['name'], $item['types']);

        return new self(
            $item['id'],
            $item['name'],
            $types
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTypes(): array
    {
        return $this->types;
    }
}

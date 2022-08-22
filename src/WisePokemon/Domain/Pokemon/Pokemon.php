<?php

declare(strict_types=1);

namespace App\WisePokemon\Domain\Pokemon;

class Pokemon
{
    private readonly \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        private readonly PokemonId $id,
        private readonly PokemonImportId $importId,
        private PokemonName $name,
        private array $types,
    ) {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public static function create(PokemonImportId $importId, PokemonName $name, array $types): Pokemon
    {
        return new self(
            PokemonId::withPrefix((string)$importId->getValue()),
            $importId,
            $name,
            $types,
        );
    }

    public function getId(): PokemonId
    {
        return $this->id;
    }

    public function getImportId(): PokemonImportId
    {
        return $this->importId;
    }

    public function getName(): PokemonName
    {
        return $this->name;
    }

    public function getTypes(): array
    {
        return \array_map(static fn (string $type) => new PokemonType($type), $this->types);
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function update(PokemonName $name, array $types): void
    {
        $this->name = $name;
        $this->types = $types;

        $this->updatedAt = new \DateTimeImmutable();
    }
}

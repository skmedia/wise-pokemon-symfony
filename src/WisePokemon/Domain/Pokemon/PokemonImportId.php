<?php

declare(strict_types=1);

namespace App\WisePokemon\Domain\Pokemon;

use App\WisePokemon\Infrastructure\Exception\InvalidArgumentException;

class PokemonImportId implements \JsonSerializable
{
    private readonly int $value;

    public function __construct(mixed $value)
    {
        if (!\is_int($value)) {
            throw new InvalidArgumentException('import id must be numeric');
        }

        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }

    public function jsonSerialize(): mixed
    {
        return $this->value;
    }
}

<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Write\Pokemon\AddPokemon;

use App\WisePokemon\Domain\Pokemon\Pokemon;
use App\WisePokemon\Domain\Pokemon\PokemonImportId;
use App\WisePokemon\Domain\Pokemon\PokemonName;

use App\WisePokemon\Domain\Pokemon\PokemonType;
use App\WisePokemon\Infrastructure\Message\Message;
use Symfony\Component\Validator\Constraints as Assert;
use App\WisePokemon\Infrastructure\Validation as AppAssert;

#[AppAssert\UniqueValueInEntity(entityClass: Pokemon::class, field: "importId", message: "importId must be unique", )]
class AddPokemon implements Message
{
    public function __construct(
        #[Assert\NotBlank(message: "import id is required")]
        private readonly int $importId,
        #[Assert\NotBlank(message: "name is required")]
        private readonly string $name,
        #[Assert\NotBlank(message: "types is required")]
        private readonly array $types,
    ) {
    }

    public function getName(): PokemonName
    {
        return new PokemonName($this->name);
    }

    /**
     * @return PokemonType[]
     */
    public function getTypes(): array
    {
        return array_map(static fn (string $type) => new PokemonType($type), $this->types);
    }

    public function getImportId(): PokemonImportId
    {
        return new PokemonImportId($this->importId);
    }
}

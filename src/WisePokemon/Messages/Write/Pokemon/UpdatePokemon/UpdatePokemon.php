<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Write\Pokemon\UpdatePokemon;

use App\WisePokemon\Domain\Pokemon\PokemonId;
use App\WisePokemon\Domain\Pokemon\PokemonName;

use App\WisePokemon\Domain\Pokemon\PokemonType;
use App\WisePokemon\Infrastructure\Message\Message;
use Symfony\Component\Validator\Constraints as Assert;

class UpdatePokemon implements Message
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

    public function getId(): PokemonId
    {
        return PokemonId::withPrefix((string)$this->importId);
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
}

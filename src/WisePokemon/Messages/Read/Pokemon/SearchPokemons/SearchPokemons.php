<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Read\Pokemon\SearchPokemons;

use App\WisePokemon\Infrastructure\Message\Message;
use Symfony\Component\Validator\Constraints as Assert;

class SearchPokemons implements Message
{
    public function __construct(
        #[Assert\NotBlank(message: "query is required")]
        private readonly string $query,
        #[Assert\Type(type: "numeric", message: "limit is required")]
        private readonly int $limit = 5,
    ) {
    }

    public function getQuery(): string
    {
        return \trim($this->query);
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
}

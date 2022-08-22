<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Read\Pokemon\GetPokemonListingV2;

use App\WisePokemon\Infrastructure\Message\Message;
use Symfony\Component\Validator\Constraints as Assert;

class GetPokemonListing implements Message, \JsonSerializable
{
    public function __construct(
        #[Assert\NotBlank(message: "sort is required")]
        #[Assert\Choice(choices: [
            'name-asc',
            'name-desc',
            'id-asc',
            'id-desc'
        ], message: "invalid sort field")]
        private readonly string $sort,
        #[Assert\NotBlank(message: "limit is required")]
        #[Assert\Type(type: "numeric", message: "limit must be numeric")]
        private readonly int $limit,
        #[Assert\NotBlank(message: "offset is required")]
        #[Assert\Type(type: "numeric", message: "offset must be numeric")]
        private readonly int $offset,
    ) {
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    /** @return array{field: string, dir: string} */
    public function getSort(): array
    {
        list($sortField, $sortDir) = \explode('-', $this->sort);

        return [
            'field' => $sortField,
            'dir' => $sortDir,
        ];
    }

    public function jsonSerialize(): array
    {
        return \get_object_vars($this);
    }
}

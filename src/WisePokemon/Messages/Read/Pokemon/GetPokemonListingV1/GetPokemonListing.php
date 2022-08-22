<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Read\Pokemon\GetPokemonListingV1;

use App\WisePokemon\Infrastructure\Message\Message;
use Symfony\Component\Validator\Constraints as Assert;

class GetPokemonListing implements Message
{
    public function __construct(
        #[Assert\NotBlank(message: "sort is required")]
        #[Assert\Choice(choices: [
            'name-asc',
            'name-desc',
            'id-asc',
            'id-desc'
        ], message: "invalid sort field")]
        private readonly string $sort
    ) {
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
}

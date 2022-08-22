<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\Api\Result;

class Metadata implements \JsonSerializable
{
    public function __construct(
        private readonly string $next,
        private readonly string $previous,
        private readonly int $total,
        private readonly int $pages,
        private readonly int $page,
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return \get_object_vars($this);
    }
}

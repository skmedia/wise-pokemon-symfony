<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\Api\Result;

class PaginatedResult implements \JsonSerializable
{
    public function __construct(
        private readonly array $data,
        private readonly Metadata $metadata,
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'data' => $this->data,
            'metadata' => $this->metadata,
        ];
    }
}

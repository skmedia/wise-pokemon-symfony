<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Write\Pokemon\ImportPokemonFromApi;

use App\WisePokemon\Infrastructure\Message\Message;
use Symfony\Component\Console\Output\OutputInterface;

class ImportPokemonFromApi implements Message
{
    public function __construct(
        private readonly string $nameOrId,
        private readonly OutputInterface $output,
    ) {
    }

    public function getNameOrId(): string
    {
        return $this->nameOrId;
    }

    public function getOutput(): OutputInterface
    {
        return $this->output;
    }
}

<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Write\Pokemon\ImportPokemonsFromFile;

use App\WisePokemon\Infrastructure\Message\Message;
use Symfony\Component\Console\Output\OutputInterface;

class ImportPokemonsFromFile implements Message
{
    public function __construct(
        private readonly string $filename,
        private readonly OutputInterface $output,
    ) {
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getOutput(): OutputInterface
    {
        return $this->output;
    }
}

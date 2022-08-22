<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\Console;

use App\WisePokemon\Messages\Write\Pokemon\ImportPokemonsFromFile\ImportPokemonsFromFile;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ImportPokemonsConsole extends Command
{
    public function __construct(
        private readonly MessageBusInterface $writeBus,
        private readonly string $importDataDir,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setName('pokemons:import:file')
            ->setDescription('Import pokemons from json');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->writeBus->dispatch(new ImportPokemonsFromFile(
            $this->importDataDir . '/pokemons.json',
            $output
        ));

        return Command::SUCCESS;
    }
}

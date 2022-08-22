<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\Console;

use App\WisePokemon\Messages\Write\Pokemon\ImportPokemonFromApi\ImportPokemonFromApi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ImportPokemonFromApiConsole extends Command
{
    public function __construct(
        private readonly MessageBusInterface $writeBus,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setName('pokemon:import:api')
            ->setDescription('Import a single pokemon from the api, by id, or name')
            ->addArgument('idOrName', InputArgument::REQUIRED, 'id or name of the pokemon to import from the api')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $idOrName = $input->getArgument('idOrName');

        if (empty($idOrName)) {
            $output->writeln('Please provide an id or name');
            return Command::INVALID;
        }

        $this->writeBus->dispatch(new ImportPokemonFromApi(
            $input->getArgument('idOrName'),
            $output
        ));

        return Command::SUCCESS;
    }
}

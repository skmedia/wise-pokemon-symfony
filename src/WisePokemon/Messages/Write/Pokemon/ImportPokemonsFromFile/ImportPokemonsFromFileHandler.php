<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Write\Pokemon\ImportPokemonsFromFile;

use App\WisePokemon\Domain\Pokemon\PokemonId;
use App\WisePokemon\Domain\Pokemon\PokemonRepository;
use App\WisePokemon\Infrastructure\Integration\PokemonApi\PokemonApiItem;
use App\WisePokemon\Messages\Write\Pokemon\AddPokemon\AddPokemon;
use Doctrine\ORM\EntityManagerInterface;
use JsonMachine\Items;
use JsonMachine\JsonDecoder\ExtJsonDecoder;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ImportPokemonsFromFileHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly MessageBusInterface $writeBus,
        private readonly PokemonRepository $pokemonRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(ImportPokemonsFromFile $message)
    {
        $pokemons = Items::fromFile($message->getFilename(), [
            'decoder' => new ExtJsonDecoder(true)
        ]);

        $i = 0;
        $batchSize = 50;
        foreach ($pokemons as $pokemonRow) {
            $pokemonToImport = PokemonApiItem::create($pokemonRow);

            $found = $this->pokemonRepository->findOneById(
                PokemonId::withPrefix($pokemonToImport->getId())
            );
            if ($found) {
                $message->getOutput()->writeln('already in db: ' . $pokemonToImport->getName());
                continue; // or update?
            }

            $message->getOutput()->writeln('adding: ' . $pokemonToImport->getName());
            $this->writeBus->dispatch(new AddPokemon(
                $pokemonToImport->getId(),
                $pokemonToImport->getName(),
                $pokemonToImport->getTypes(),
            ));

            if ((++$i % $batchSize) === 0) {
                $this->entityManager->flush();
                $this->entityManager->clear();
            }
        }

        $this->entityManager->flush();
        $this->entityManager->clear();
    }
}

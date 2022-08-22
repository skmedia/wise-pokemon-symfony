<?php

declare(strict_types=1);

namespace App\WisePokemon\Messages\Write\Pokemon\ImportPokemonFromApi;

use App\WisePokemon\Domain\Pokemon\PokemonId;
use App\WisePokemon\Domain\Pokemon\PokemonRepository;
use App\WisePokemon\Infrastructure\Integration\PokemonApi\PokemonApiItem;
use App\WisePokemon\Messages\Write\Pokemon\AddPokemon\AddPokemon;
use App\WisePokemon\Messages\Write\Pokemon\UpdatePokemon\UpdatePokemon;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ImportPokemonFromApiHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly MessageBusInterface $writeBus,
        private readonly PokemonRepository $pokemonRepository,
        private readonly HttpClientInterface $pokemonApiClient
    ) {
    }

    public function __invoke(ImportPokemonFromApi $message)
    {
        try {
            $response = $this->pokemonApiClient->request(
                Request::METHOD_GET,
                '/api/v2/pokemon/' . $message->getNameOrId()
            );

            $pokemonFromApi = PokemonApiItem::create($response->toArray());

            $pokemon = $this->pokemonRepository->findOneById(
                PokemonId::withPrefix($pokemonFromApi->getId())
            );

            if ($pokemon) {
                $this->writeBus->dispatch(new UpdatePokemon(
                    $pokemonFromApi->getId(),
                    $pokemonFromApi->getName(),
                    $pokemonFromApi->getTypes(),
                ));

                $message->getOutput()->writeln('Pokemon already exists, updating: ' . $pokemonFromApi->getName());
                return;
            }

            $message->getOutput()->writeln('Add pokemon: ' . $pokemonFromApi->getName());
            $this->writeBus->dispatch(new AddPokemon(
                $pokemonFromApi->getId(),
                $pokemonFromApi->getName(),
                $pokemonFromApi->getTypes(),
            ));
        } catch (ClientException $e) {
            $message->getOutput()->writeln('Api error: ' . $e->getMessage() . ' / code = ' . $e->getResponse()->getStatusCode());
        }
    }
}

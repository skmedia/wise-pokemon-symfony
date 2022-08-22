<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\Controller\Api\Pokemon;

use App\WisePokemon\Infrastructure\Message\MessageDispatcher;
use App\WisePokemon\Infrastructure\Message\MessageFactory;
use App\WisePokemon\Messages\Read\Pokemon\GetPokemonById\GetPokemonById;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class GetPokemonByIdController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $readBus,
        private readonly MessageDispatcher $messageDispatcher,
        private readonly MessageFactory $messageFactory,
    ) {
    }

    #[Route('/api/v1/pokemons/{id}', name: "pokemon_by_id", methods: ['GET'])]
    public function __invoke(Request $request): JsonResponse
    {
        $message = $this->messageFactory->createFromParams(GetPokemonById::class, [
            'pokemonId' => $request->attributes->get('id', ''),
        ]);

        return $this->messageDispatcher->dispatch($this->readBus, $message);
    }
}

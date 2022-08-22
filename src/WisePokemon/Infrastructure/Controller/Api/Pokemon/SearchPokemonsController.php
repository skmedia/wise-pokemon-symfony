<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\Controller\Api\Pokemon;

use App\WisePokemon\Infrastructure\Message\MessageDispatcher;
use App\WisePokemon\Infrastructure\Message\MessageFactory;
use App\WisePokemon\Messages\Read\Pokemon\SearchPokemons\SearchPokemons;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class SearchPokemonsController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $readBus,
        private readonly MessageDispatcher $messageDispatcher,
        private readonly MessageFactory $messageFactory,
    ) {
    }

    #[Route('/api/v1/search', name: "pokemon_search", methods: ['GET'])]
    public function __invoke(Request $request): JsonResponse
    {
        $message = $this->messageFactory->createFromParams(SearchPokemons::class, [
            'query' => $request->query->get('query', ''),
            'limit' => $request->query->getInt('limit', 5),
        ]);

        return $this->messageDispatcher->dispatch($this->readBus, $message);
    }
}

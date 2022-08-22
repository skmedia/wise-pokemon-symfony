<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\Controller\Api\Pokemon;

use App\WisePokemon\Infrastructure\Message\MessageDispatcher;
use App\WisePokemon\Infrastructure\Message\MessageFactory;
use App\WisePokemon\Messages\Read\Pokemon\GetPokemonListingV2\GetPokemonListing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class GetPokemonListingV2Controller extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $readBus,
        private readonly MessageDispatcher $messageDispatcher,
        private readonly MessageFactory $messageFactory,
    ) {
    }

    #[Route('/api/v2/pokemons', name: "pokemon_listing_v2", methods: ['GET'])]
    public function __invoke(Request $request): JsonResponse
    {
        $message = $this->messageFactory->createFromParams(GetPokemonListing::class, [
            'sort' => $request->query->get('sort', ''),
            'offset' => $request->query->getInt('offset', 0),
            'limit' => $request->query->getInt('limit', 10),
        ]);

        return $this->messageDispatcher->dispatch($this->readBus, $message);
    }
}

<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\Controller\Api\Team;

use App\WisePokemon\Infrastructure\Message\MessageDispatcher;
use App\WisePokemon\Infrastructure\Message\MessageFactory;
use App\WisePokemon\Messages\Write\Team\AssignPokemonsToTeam\AssignPokemonsToTeam;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class AssignPokemonsToTeamController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $writeBus,
        private readonly MessageDispatcher $messageDispatcher,
        private readonly MessageFactory $messageFactory,
    ) {
    }

    #[Route('/api/v1/teams/{id}', name: "assign_pokemons_to_team", methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $payload = [
            'id' => $request->attributes->get('id', ''),
            ...$request->toArray()
        ];

        $message = $this->messageFactory->createFromParams(AssignPokemonsToTeam::class, $payload);

        $this->messageDispatcher->dispatch($this->writeBus, $message);

        return $this->json('ok');
    }
}

<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\Controller\Api\Team;

use App\WisePokemon\Infrastructure\Message\MessageDispatcher;
use App\WisePokemon\Infrastructure\Message\MessageFactory;
use App\WisePokemon\Messages\Read\Team\GetTeamById\GetTeamById;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class GetTeamByIdController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $readBus,
        private readonly MessageDispatcher $messageDispatcher,
        private readonly MessageFactory $messageFactory,
    ) {
    }

    #[Route('/api/v1/teams/{id}', name: "get_team_by_id", methods: ['GET'])]
    public function __invoke(Request $request): JsonResponse
    {
        $message = $this->messageFactory->createFromParams(GetTeamById::class, [
            'teamId' => $request->attributes->get('id', ''),
        ]);

        return $this->messageDispatcher->dispatch($this->readBus, $message);
    }
}

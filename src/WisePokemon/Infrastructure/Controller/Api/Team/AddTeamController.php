<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\Controller\Api\Team;

use App\WisePokemon\Infrastructure\Message\MessageDispatcher;
use App\WisePokemon\Infrastructure\Message\MessageFactory;
use App\WisePokemon\Messages\Write\Team\AddTeam\AddTeam;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class AddTeamController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $writeBus,
        private readonly MessageDispatcher $messageDispatcher,
        private readonly MessageFactory $messageFactory,
    ) {
    }

    #[Route('/api/v1/teams', name: "add_team", methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $message = $this->messageFactory->createFromParams(AddTeam::class, $request->toArray());

        $this->messageDispatcher->dispatch($this->writeBus, $message);

        return $this->json('ok');
    }
}

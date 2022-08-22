<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\Controller\Api\Team;

use App\WisePokemon\Infrastructure\Message\MessageDispatcher;
use App\WisePokemon\Infrastructure\Message\MessageFactory;
use App\WisePokemon\Messages\Read\Team\GetTeamListing\GetTeamListing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class GetTeamListingController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $readBus,
        private readonly MessageDispatcher $messageDispatcher,
        private readonly MessageFactory $messageFactory,
    ) {
    }

    #[Route('/api/v1/teams', name: "team_listing", methods: ['GET'])]
    public function __invoke(Request $request): JsonResponse
    {
        $message = $this->messageFactory->createFromParams(GetTeamListing::class);

        return $this->messageDispatcher->dispatch($this->readBus, $message);
    }
}

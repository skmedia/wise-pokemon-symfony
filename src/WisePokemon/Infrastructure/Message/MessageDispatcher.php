<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\Message;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Serializer\SerializerInterface;

class MessageDispatcher
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function dispatch(MessageBusInterface $bus, Message $message): JsonResponse
    {
        $envelope = $bus->dispatch($message);

        $handledStamp = $envelope->last(HandledStamp::class);
        $responseData = $handledStamp instanceof HandledStamp ? $handledStamp->getResult() : null;

        $responseData = $this->serializer->serialize($responseData, 'json');

        return JsonResponse::fromJsonString($responseData ?: '');
    }

    public function dispatchBinary(MessageBusInterface $bus, Message $message): BinaryFileResponse
    {
        $envelope = $bus->dispatch($message);

        $handledStamp = $envelope->last(HandledStamp::class);
        $responseData = $handledStamp instanceof HandledStamp ? $handledStamp->getResult() : null;

        if (\is_null($responseData) || !\file_exists($responseData)) {
            throw new \Exception('No file found');
        }

        $response = (new BinaryFileResponse($responseData));

        if (!$message instanceof KeepBinaryFile) {
            $response->deleteFileAfterSend();
        }

        return $response;
    }
}

<?php

declare(strict_types=1);

namespace App\Tests;

use App\WisePokemon\Infrastructure\Message\Message;
use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

abstract class DatabaseTestCase extends WebTestCase
{
    protected static ?KernelBrowser $client = null;
    protected readonly EntityManagerInterface $entityManager;

    protected ?AbstractDatabaseTool $databaseTool = null;

    protected function setUp(): void
    {
        parent::setUp();

        static::$client = self::createClient();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();

        $container = self::getContainer();

        /** @var EntityManagerInterface $entityManager */
        $this->entityManager = $container->get(EntityManagerInterface::class);
    }

    /**
     * @return Crawler
     */
    protected function postRequest(string $url, array $payload = []): Crawler
    {
        return self::$client->xmlHttpRequest(
            'POST',
            $url,
            $payload,
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
        );
    }

    /**
     * @return Crawler
     */
    protected function getRequest(string $url, array $payload = []): Crawler
    {
        return self::$client->xmlHttpRequest(
            'GET',
            $url,
            $payload,
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
        );
    }

    protected function dispatchReadMessage(Message $message)
    {
        return $this->dispatchMessage($message, 'read');
    }

    protected function dispatchWriteMessage(Message $message)
    {
        return $this->dispatchMessage($message, 'write');
    }

    protected function dispatchMessage(Message $message, string $busType = 'read')
    {
        /** @var MessageBusInterface $bus */
        $bus = self::getContainer()->get('debug.traced.'.$busType.'.bus');
        $envelope = $bus->dispatch($message);
        $handledStamp = $envelope->last(HandledStamp::class);
        $result = $handledStamp instanceof HandledStamp ? $handledStamp->getResult() : null;

        return $result;
    }

    protected function logResponse()
    {
        \file_put_contents(__DIR__. '/response.html', self::$client->getResponse()->getContent());
    }
}

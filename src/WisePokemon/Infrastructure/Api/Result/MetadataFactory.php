<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\Api\Result;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MetadataFactory
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function create(
        string $route,
        array $params,
        int $total,
        int $offset,
        int $limit
    ): Metadata {
        $pages = (int)ceil($total / $limit);
        $page = (int)ceil(($offset - 1) / $limit) + 1;

        $nextOffset = $offset + $limit <= $total ? $offset + $limit : $offset;
        $next = $this->urlGenerator->generate($route, [
            ...$params,
            'offset' => $nextOffset,
            'limit' => $limit,
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $previousOffset = max($offset - $limit, 0);
        $previous = $this->urlGenerator->generate($route, [
            ...$params,
            'offset' => $previousOffset,
            'limit' => $limit,
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        return new Metadata(
            $next,
            $previous,
            $total,
            $pages,
            $page,
        );
    }
}

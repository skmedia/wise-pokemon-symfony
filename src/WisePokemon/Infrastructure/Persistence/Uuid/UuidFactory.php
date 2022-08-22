<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\Persistence\Uuid;

use Symfony\Component\Uid\UuidV4;

class UuidFactory
{
    public static function random(): string
    {
        return (new UuidV4())->toRfc4122();
    }

    public static function shortRandom(): string
    {
        return (new UuidV4())->toRfc4122();
    }
}

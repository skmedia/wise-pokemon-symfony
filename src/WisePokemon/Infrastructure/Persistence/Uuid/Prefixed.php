<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\Persistence\Uuid;

interface Prefixed
{
    public static function getPrefix(): string;
}

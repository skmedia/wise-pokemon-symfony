<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\Exception;

final class InvalidArgumentException extends \InvalidArgumentException
{
    public static function doesNotImplement(string $class, string $interface): InvalidArgumentException
    {
        return new static("Object of class $class should implement $interface");
    }

    public static function notOneOf(string $given, array $available): InvalidArgumentException
    {
        return new static("'$given' is not not a valid argument. Should be one of ['".implode(',', $available)."'].");
    }
}

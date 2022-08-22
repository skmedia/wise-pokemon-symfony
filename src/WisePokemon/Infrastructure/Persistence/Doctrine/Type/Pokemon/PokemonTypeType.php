<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\Persistence\Doctrine\Type\Pokemon;

use App\WisePokemon\Domain\Pokemon\PokemonType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class PokemonTypeType extends Type
{
    public const NAME = 'pokemon_type';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        if (null === $value || $value instanceof PokemonType) {
            return $value;
        }

        return new PokemonType($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        return (string) $value;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}

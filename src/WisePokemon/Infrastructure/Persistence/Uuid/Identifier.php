<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\Persistence\Uuid;

use InvalidArgumentException;
use JsonSerializable;

abstract class Identifier implements Prefixed, JsonSerializable
{
    /**
     * @var string
     */
    protected string $identifier;

    final public function __construct(string $identifier)
    {
        $this->validate($identifier);
        $this->identifier = $identifier;
    }

    public function isValid(string $identifier): bool
    {
        try {
            $this->validate($identifier);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @return static
     */
    public static function random(): static
    {
        $prefix = '';
        if (!empty(static::getPrefix())) {
            $prefix = static::getPrefix().'-';
        }

        return new static($prefix.UuidFactory::random());
    }

    /**
     * @param string $string
     *
     * @return static
     */
    public static function fromString(string $string): static
    {
        return new static($string);
    }

    public function __toString(): string
    {
        return $this->identifier;
    }

    public function jsonSerialize(): string
    {
        return (string) $this;
    }

    /**
     * @param Identifier $other
     */
    public function equals(Identifier $other = null): bool
    {
        if (null === $other) {
            return false;
        }

        return $this->identifier === $other->identifier;
    }

    protected function validate(string $identifier): void
    {
        if (empty($identifier)) {
            throw new InvalidArgumentException('Non-empty string expected for Identifier, got: '.$identifier);
        }
        if ('' !== static::getPrefix() && 0 !== strpos($identifier, $this->getPrefix())) {
            throw new InvalidArgumentException('Identifier does not start with prefix "'.static::getPrefix().'", got: '.$identifier);
        }
    }

    /**
     * @param array $identifierStrings
     *
     * @return Identifier[]
     */
    public static function map($identifierStrings): array
    {
        $identifiers = [];
        foreach ($identifierStrings as $identifierString) {
            $identifiers[] = new static($identifierString);
        }

        return $identifiers;
    }

    public static function withPrefix(mixed $identifier): static
    {
        return static::fromString(static::getPrefix().'-'.(string)$identifier);
    }
}

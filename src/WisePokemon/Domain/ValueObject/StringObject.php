<?php

declare(strict_types=1);

namespace App\WisePokemon\Domain\ValueObject;

class StringObject implements \JsonSerializable
{
    /**
     * @var string
     */
    protected string $value;

    public function __construct(string $value)
    {
        $this->value = trim($value);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(StringObject $other): bool
    {
        return $this->value === $other->getValue();
    }

    public function toLower(): string
    {
        return \strtolower($this->value);
    }

    public function isEmpty(): bool
    {
        return $this->value === '';
    }

    public function jsonSerialize(): mixed
    {
        return $this->value;
    }
}

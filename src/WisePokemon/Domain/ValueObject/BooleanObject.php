<?php

declare(strict_types=1);

namespace App\WisePokemon\Domain\ValueObject;

class BooleanObject
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @var bool
     */
    private bool $booleanValue;

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
        $this->booleanValue = $this->toBool($this->value);
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    public function getBooleanValue(): bool
    {
        return $this->booleanValue;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    private function toBool($value): bool
    {
        return $value === '1'
            || $value === 1
            || $value === 'true'
            || $value === 'TRUE'
            || $value === 'yes'
            || $value === 'Y'
            || $value === 'y'
            || $value === 'J'
            || $value === 'j'
            || $value === 'JA'
            || $value === 'ja'
            || $value === true;
    }

    public function isTrue(): bool
    {
        return $this->getBooleanValue();
    }

    public function isFalse(): bool
    {
        return !$this->getBooleanValue();
    }
}

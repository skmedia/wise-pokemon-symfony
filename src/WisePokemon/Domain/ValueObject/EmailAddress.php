<?php

declare(strict_types=1);

namespace App\WisePokemon\Domain\ValueObject;

use App\WisePokemon\Infrastructure\Exception\InvalidArgumentException;

class EmailAddress implements \JsonSerializable
{
    private string $emailAddress;

    public function __construct(string $emailAddress)
    {
        $emailAddress = trim($emailAddress);
        $validator = new EmailAddressValidator($emailAddress, EmailAddressValidator::RFC_5322);
        if (!$validator->isValid()) {
            throw new InvalidArgumentException('Invalid EmailAddress: ' . $emailAddress);
        }
        $this->emailAddress = $emailAddress;
    }

    /**
     * Returns the local part of the email address
     * @return string
     */
    public function getLocalPart(): string
    {
        $parts = explode('@', $this->emailAddress);
        return $parts[0];
    }

    /**
     * Returns the domain part of the email address
     * @return string
     */
    public function getDomainPart(): string
    {
        $parts = \explode('@', $this->emailAddress);
        return $parts[1];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->emailAddress;
    }

    /**
     * @param EmailAddress $other
     * @return bool
     */
    public function equals(EmailAddress $other)
    {
        return $this->emailAddress === $other->emailAddress;
    }

    /**
     * @return string
     */
    public function jsonSerialize(): mixed
    {
        return $this->__toString();
    }
}

<?php

declare(strict_types=1);

namespace App\WisePokemon\Domain\User;

use App\WisePokemon\Domain\ValueObject\EmailAddress;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class User
{
    private string $password;
    private Collection $pokemons;
    private readonly \DateTimeImmutable $createdAt;
    private readonly \DateTimeImmutable $updatedAt;

    public function __construct(
        private readonly UserId $id,
        private readonly EmailAddress $email,
        private readonly UserName $name,
    ) {
        $this->pokemons = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getEmail(): EmailAddress
    {
        return $this->email;
    }

    public function getName(): UserName
    {
        return $this->name;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}

<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\Message;

interface AuthorizedMessage
{
    public function getPermissions(): array;
}

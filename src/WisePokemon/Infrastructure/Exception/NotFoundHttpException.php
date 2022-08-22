<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException as SymfonyNotFoundHttpException;

class NotFoundHttpException extends SymfonyNotFoundHttpException
{
}

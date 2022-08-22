<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\EventListener;

use App\WisePokemon\Infrastructure\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Validator\ConstraintViolationInterface;

class JsonExceptionListener
{
    public function __construct(private readonly string $appEnv)
    {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();

        $previousException = null;
        if ($exception instanceof HandlerFailedException) {
            $previousException = $exception->getPrevious();
        }

        if ($exception instanceof ValidationFailedException) {
            $errors = [];
            /** @var ConstraintViolationInterface $violation */
            foreach ($exception->getViolations() as $violation) {
                $path = $violation->getPropertyPath() ?: '_root';
                $errors[$path][] = $violation->getMessage();
            }
            $response = new JsonResponse(
                [
                    'errors' => $errors,
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
            $response->setEncodingOptions(\JSON_OBJECT_AS_ARRAY);
            $event->setResponse($response);

            return;
        }

        if ($previousException instanceof NotFoundHttpException) {
            $response = new JsonResponse(
                [
                    'errors' => [
                        $previousException->getMessage(),
                    ],
                ],
                JsonResponse::HTTP_NOT_FOUND
            );
            $response->headers->set('Content-Type', 'application/problem+json');
            $event->setResponse($response);

            return;
        }

        if ($request->headers->get('Content-Type') === 'application/json') {
            $response = new JsonResponse(
                $this->appEnv === 'dev' ? $exception->getMessage() : null,
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
            $response->headers->set('Content-Type', 'application/problem+json');
            $event->setResponse($response);

            return;
        }
    }
}

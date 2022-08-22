<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\Message;

use Doctrine\Inflector\InflectorFactory;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class MessageFactory
{
    public function __construct(
        private string $namespaceTemplate = "App\\%%s\\Messages\\%%s\\",
    ) {
    }

    public function createFromRequest(Request $request): Message
    {
        if ($request->isMethod(Request::METHOD_GET)) {
            $requestParameters = $request->query;
        } else {
            $requestParameters = $request->request;
        }

        $this->guardRequest($requestParameters);

        /** @var string $messageName */
        $messageName = $requestParameters->get('messageName', '');
        /** @var class-string $fqcn */
        $fqcn = $this->normalize($messageName);
        $this->guardThatMessageClassExists($fqcn);

        $reflectionClass = new ReflectionClass($fqcn);
        $this->guardThatMessageHasConstructor($reflectionClass);

        $parameters = $this->getParametersFromConstructor($reflectionClass);

        if (!empty($request->getContent()) && $request->getContentType() === 'json') {
            $payload = $request->toArray();
        } else {
            $payload = $requestParameters->get('payload', '[]');
            if ($request->isMethod(Request::METHOD_GET)) {
                $payload = \json_decode((string)$payload, true, 512, JSON_THROW_ON_ERROR);
            }
        }
        if (!\is_array($payload)) {
            $payload = [];
        }
        $arguments = $this->buildArgumentList($payload, $parameters);

        /** @var Message $message */
        $message = $reflectionClass->newInstanceArgs($arguments);

        return $message;
    }

    private function createMessageClass(string $message, string $messageType): string
    {
        $inflector = InflectorFactory::create()->build();

        $messageParts = \explode('/', $message);
        $messagePartsClassified = \array_map(static fn ($part) => $inflector->classify($part), $messageParts);

        $module = \array_shift($messagePartsClassified);
        $messagePartsClassified[] = \end($messagePartsClassified); // Add message class name again

        $corePrefix = \sprintf($this->namespaceTemplate, $inflector->classify($module), $messageType);
        $messageName = $corePrefix . \implode('\\', $messagePartsClassified);

        return $messageName;
    }

    public function createFromRoutePath(
        string $message,
        Request $request,
        string $messageType
    ): Message {
        $inflector = InflectorFactory::create()->build();
        $messageType = $inflector->capitalize($messageType);

        $messageName = $this->createMessageClass($message, $messageType);

        /*
        $namespaceTemplate = $this->namespaceTemplate;
        $parts = \explode('/', $message);
        $d = \array_map(static fn ($part) => $inflector->classify($part), $parts);
        $namespace = \array_shift($d);
        $d[] = \end($d);
        $corePrefix = \sprintf($namespaceTemplate, $inflector->classify($namespace), $messageType);
        $messageName = $corePrefix . \implode('\\', $d);
        */

        if ($request->isMethod(Request::METHOD_GET)) {
            $requestParameters = $request->query;
        } else {
            $requestParameters = $request->request;
        }

        /** @var class-string $fqcn */
        $fqcn = $this->normalize($messageName);
        $this->guardThatMessageClassExists($fqcn);

        $reflectionClass = new ReflectionClass($fqcn);
        $this->guardThatMessageHasConstructor($reflectionClass);

        $parameters = $this->getParametersFromConstructor($reflectionClass);

        if (!empty($request->getContent()) && $request->getContentType() === 'json') {
            $payload = $request->toArray();
        } else {
            $payload = $requestParameters->get('payload', '[]');
            if ($request->isMethod(Request::METHOD_GET)) {
                $payload = \json_decode((string)$payload, true) ?: [];
            }
        }
        if (!\is_array($payload)) {
            $payload = [];
        }
        $arguments = $this->buildArgumentList($payload, $parameters);

        /** @var Message $message */
        $message = $reflectionClass->newInstanceArgs($arguments);

        return $message;
    }

    /**
     * @return mixed
     */
    public function createFromParams(string $messageClass, array $payload = [])
    {
        $this->guardThatMessageClassExists($messageClass);

        /** @var class-string $class */
        $class = $messageClass;
        $reflectionClass = new ReflectionClass($class);
        $this->guardThatMessageHasConstructor($reflectionClass);

        $parameters = $this->getParametersFromConstructor($reflectionClass);
        $arguments = $this->buildArgumentList($payload, $parameters);

        /** @var Message $message */
        $message = $reflectionClass->newInstanceArgs($arguments);

        return $message;
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     */
    public function createFromJson(string $json): Message
    {
        try {
            $data = \json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new \RuntimeException('json string is not valid: ' . $json);
        }

        /** @var class-string $messageClass */
        $messageClass = $data['messageName'] ?? '';
        $payload = $data['payload'] ?? [];

        $this->guardThatMessageClassExists($messageClass);

        $reflectionClass = new ReflectionClass($messageClass);
        $this->guardThatMessageHasConstructor($reflectionClass);

        $parameters = $this->getParametersFromConstructor($reflectionClass);
        $arguments = $this->buildArgumentList($payload, $parameters);

        /** @var Message $message */
        $message = $reflectionClass->newInstanceArgs($arguments);

        return $message;
    }

    private function guardRequest(ParameterBag $request): void
    {
        if (!$request->has('messageName')) {
            throw new \InvalidArgumentException('messageName not set in request');
        }
    }

    private function normalize(string $fqcn): string
    {
        $fqcn = str_replace('.', '\\', trim($fqcn));

        return ('\\' !== $fqcn[0]) ? '\\' . $fqcn : $fqcn;
    }

    private function guardThatMessageClassExists(string $fqcn): void
    {
        if (!class_exists($fqcn)) {
            throw new \InvalidArgumentException("Class $fqcn does not exist. Did you include the full FQCN? Did you properly escape backslashes?");
        }
    }

    private function guardThatMessageHasConstructor(ReflectionClass $reflectionClass): void
    {
        if (!$reflectionClass->getConstructor()) {
            throw new \InvalidArgumentException('The Message does not have a constructor');
        }
    }

    private function buildArgumentList(array $payload = [], array $parameters = []): array
    {
        $arguments = [];
        $remainingProperties = $payload;

        /** @var ReflectionParameter $parameter */
        foreach ($parameters as $parameter) {
            $this->guardThatPayloadHasParameterIfRequired($parameter, $payload);
            unset($remainingProperties[$parameter->name]);
            $payloadHasParameter = array_key_exists($parameter->name, $payload);

            if ($payloadHasParameter) {
                $value = $payload[$parameter->name];
                $paramType = $parameter->getType();
                if (($paramType instanceof ReflectionNamedType) && !$paramType->isBuiltin()) {
                    if (empty($value) && $paramType->allowsNull()) {
                        $value = $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null;
                    } else {
                        /** @var class-string $name */
                        $name = $paramType->getName();
                        $reflectionClass = new ReflectionClass($name);
                        if (\is_array($value)) {
                            $parameters = $this->getParametersFromConstructor($reflectionClass);
                            $args = $this->buildArgumentList($value, $parameters);
                            $value = $reflectionClass->newInstanceArgs($args);
                        } else {
                            $value = $reflectionClass->newInstance($value);
                        }
                    }
                }
            } elseif ($parameter->isDefaultValueAvailable()) {
                $value = $parameter->getDefaultValue();
            } else {
                $value = null;
            }

            $arguments[] = $value;
        }

        $this->guardThatThereAreNoAlienProperties($remainingProperties);

        return $arguments;
    }

    private function guardThatPayloadHasParameterIfRequired(ReflectionParameter $parameter, array $payload = []): void
    {
        $payloadHasParameter = array_key_exists($parameter->getName(), $payload);

        if (!$payloadHasParameter && !$parameter->isOptional() && !$parameter->allowsNull()) {
            throw new \InvalidArgumentException(sprintf('The parameter [%s] is missing from the Message payload. Add it to the payload or make it optional in the Message constructor.', $parameter->name));
        }
    }

    private function guardThatThereAreNoAlienProperties(array $remainingProperties): void
    {
        if (!empty($remainingProperties)) {
            throw new \InvalidArgumentException(sprintf('The parameters [%s] are never used in the Message payload. Remove them from the payload or make sure the Message\'s constructor has parameters with the same name.', implode(', ', array_keys($remainingProperties))));
        }
    }

    private function getParametersFromConstructor(ReflectionClass $reflectionClass): array
    {
        $constructor = $reflectionClass->getConstructor();
        if (!$constructor) {
            return [];
        }

        return $constructor->getParameters();
    }
}

<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\Validation;

use Symfony\Component\Validator\Constraint;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute]
class EntityExists extends Constraint
{
    public string $message;
    /** @var class-string */
    public string $entityClass;
    public bool $optional = false;

    public function __construct(
        mixed $entityClass,
        string $message = 'The entity was not found',
        mixed $options = null,
        array $groups = null,
        mixed $payload = null
    ) {
        $this->message = $message;
        $this->entityClass = $entityClass;
        parent::__construct($options, $groups, $payload);
    }

    public function getTargets(): string|array
    {
        return self::PROPERTY_CONSTRAINT;
    }
}

<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\Validation;

use Symfony\Component\Validator\Constraint;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 * @Annotation
 * @Target({"CLASS", "ANNOTATION", "ATTRIBUTE"})
 */
#[\Attribute(\Attribute::TARGET_CLASS|\Attribute::IS_REPEATABLE)]
class UniqueValueInEntity extends Constraint
{
    public string $message = 'This value is already used.';
    public string $messageInvalidFieldGetter = 'Getter \'{{fieldGetter}}\' does not exist';
    /** @var class-string */
    public string $entityClass;
    public mixed $field;
    public string $idGetter = 'getId';

    public function __construct(
        mixed $entityClass,
        mixed $field,
        string $message = 'The entity was not found',
        string $idGetter = 'getId',
        mixed $options = null,
        array $groups = null,
        mixed $payload = null
    ) {
        $this->entityClass = $entityClass;
        $this->field = $field;
        $this->message = $message;
        $this->idGetter = $idGetter;

        parent::__construct($options, $groups, $payload);
    }

    public function getTargets(): string|array
    {
        return self::CLASS_CONSTRAINT;
    }
}

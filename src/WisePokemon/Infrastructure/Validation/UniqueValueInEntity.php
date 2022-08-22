<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\Validation;

use Symfony\Component\Validator\Constraint;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 * @Annotation
 * @Target({"CLASS", "ANNOTATION"})
 */
class UniqueValueInEntity extends Constraint
{
    public string $message = 'This value is already used.';
    public string $messageInvalidFieldGetter = 'Getter \'{{fieldGetter}}\' does not exist';
    /** @var class-string */
    public string $entityClass;
    public mixed $field;
    public string $idGetter = 'getId';

    public function getRequiredOptions(): array
    {
        return ['entityClass', 'field'];
    }

    public function getTargets(): string|array
    {
        return self::CLASS_CONSTRAINT;
    }
}

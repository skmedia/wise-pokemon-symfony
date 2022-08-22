<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\Validation;

use App\WisePokemon\Infrastructure\Exception\InvalidArgumentException;
use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class UniqueValueInEntityValidator extends ConstraintValidator
{
    private static ?Inflector $inflector = null;

    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @psalm-suppress MoreSpecificImplementedParamType
     *
     * @param mixed $value
     * @param UniqueValueInEntity $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (self::$inflector === null) {
            self::$inflector = InflectorFactory::create()
                ->build();
        }

        if (\is_string($constraint->field)) {
            $fieldGetter = 'get'.static::$inflector->classify($constraint->field);
            if (!\method_exists($value, $fieldGetter)) {
                $this->context->buildViolation($constraint->messageInvalidFieldGetter)
                    ->setParameter('{{fieldGetter}}', $fieldGetter)
                    ->addViolation();

                return;
            }

            /** @var ObjectRepository $entityRepository */
            $entityRepository = $this->entityManager->getRepository($constraint->entityClass);
            $foundEntity = $entityRepository->findOneBy([
                $constraint->field => $value->{$fieldGetter}(),
            ]);
        } elseif (\is_array($constraint->field)) {
            $fieldDefintion = [];
            foreach ($constraint->field as $f) {
                $fieldGetter = 'get'.static::$inflector->classify($f);
                if (!\method_exists($value, $fieldGetter)) {
                    $this->context->buildViolation($constraint->messageInvalidFieldGetter)
                        ->setParameter('{{fieldGetter}}', $fieldGetter)
                        ->addViolation();

                    return;
                }

                $fieldValue = $value->{$fieldGetter}();
                $fieldDefintion[$f] = $fieldValue;
            }

            /** @var ObjectRepository $entityRepository */
            $entityRepository = $this->entityManager->getRepository($constraint->entityClass);
            $foundEntity = $entityRepository->findOneBy($fieldDefintion);
        } else {
            throw new InvalidArgumentException(\vsprintf('%s must be a string or array', $constraint->field));
        }

        if ($foundEntity) {
            if ((string)$foundEntity->getId() !== (string)$value->{$constraint->idGetter}()) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }
    }
}

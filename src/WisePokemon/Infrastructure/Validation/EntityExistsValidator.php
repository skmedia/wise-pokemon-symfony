<?php

declare(strict_types=1);

namespace App\WisePokemon\Infrastructure\Validation;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class EntityExistsValidator extends ConstraintValidator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @psalm-suppress MoreSpecificImplementedParamType
     *
     * @param mixed        $value
     * @param EntityExists $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if ($constraint->optional && !$value) {
            return;
        }

        /** @var ObjectRepository $entityRepository */
        $entityRepository = $this->entityManager->getRepository($constraint->entityClass);
        $instance = $entityRepository->find($value);

        if (null === $instance) {
            $this->context
                ->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}

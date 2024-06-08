<?php

namespace App\Validator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsOnlyLettersValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!preg_match('/^[a-zA-Z]*$/', $value, $matches)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}
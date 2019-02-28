<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 28/02/19
 * Time: 12:27
 */

namespace App\Validator\Constraints\ckeditor;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ContainsCkeditorValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        preg_match('/script/', $value, $scripts);

        if (count($scripts) > 0) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
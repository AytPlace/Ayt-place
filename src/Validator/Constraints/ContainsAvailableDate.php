<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 24/01/19
 * Time: 20:18
 */

namespace App\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class ContainsAvailableDate extends Constraint
{
    public $message = 'L\'interval du "{{ startDate|date("d/m/Y") }}" au "{{ endDate|date("d/m/Y") }}" est déjà utiliser';

    public function validatedBy()
    {
        return \get_class($this).'Validator';
    }

}
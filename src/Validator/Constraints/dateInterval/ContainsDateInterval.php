<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 24/01/19
 * Time: 21:14
 */

namespace App\Validator\Constraints\dateInterval;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class ContainsDateInterval extends Constraint
{
    public $message = 'Cette intervalle de date n\'est pas valide';

    public function validatedBy()
    {
        return \get_class($this).'Validator';
    }
}
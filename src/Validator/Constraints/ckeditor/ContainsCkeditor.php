<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 28/02/19
 * Time: 12:27
 */

namespace App\Validator\Constraints\ckeditor;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class ContainsCkeditor extends Constraint
{
    public $message = 'Ce message contient du javascript merci de le retirer';

    public function validatedBy()
    {
        return \get_class($this).'Validator';
    }
}
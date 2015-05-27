<?php

namespace MusicBundle\Validation;

use Symfony\Component\Validator\Constraint;

/**
* @Annotation
*/
class FileRequiredConstraint extends Constraint
{
    public $message = 'This value is required.';

    public $file;

    public $path;

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function getRequiredOptions()
    {
        return ['file', 'path'];
    }
}
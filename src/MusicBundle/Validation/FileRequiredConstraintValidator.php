<?php

namespace MusicBundle\Validation;

use MusicBundle\Entity\MediaFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\PropertyAccess\PropertyAccess;

class FileRequiredConstraintValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!($value instanceof MediaFile)) {
            return;
        }

        $accessor = PropertyAccess::createPropertyAccessor();

        $file = $accessor->getValue($value, $constraint->file[0]);
        $path = $accessor->getValue($value, $constraint->path[0]);

        if ($path == null && $file == null) {
            $this->context->buildViolation($constraint->message)
                ->atPath($constraint->file[0])
                ->addViolation();
        }
    }
}
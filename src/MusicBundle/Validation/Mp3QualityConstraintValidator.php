<?php

namespace MusicBundle\Validation;

use MusicBundle\Service\AudioProcessor;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class Mp3QualityConstraintValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!($value instanceof UploadedFile)) {
            return;
        }

        $info = AudioProcessor::getMP3BitRateSampleRate($value->getRealPath());

        if ($info['bitRate'] < $constraint->minBitRate[0]) {
            $this->context->buildViolation($constraint->bitRateMessage)
                ->setParameter('bitRate', $info['bitRate'])
                ->setParameter('minBitRate', $constraint->minBitRate[0])
                ->addViolation();
        }

        if ($info['sampleRate'] < $constraint->minSampleRate[0]) {
            $this->context->buildViolation($constraint->sampleRateMessage)
                ->setParameter('sampleRate', $info['sampleRate'])
                ->setParameter('minSampleRate', $constraint->minSampleRate[0])
                ->addViolation();
        }
    }
}
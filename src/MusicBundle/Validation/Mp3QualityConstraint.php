<?php

namespace MusicBundle\Validation;

use Symfony\Component\Validator\Constraint;

/**
* @Annotation
*/
class Mp3QualityConstraint extends Constraint
{
    public $bitRateMessage = 'The MP3 file has a bit rate of bitRatekbps, must have a minimum of minBitRatekbps.';

    public $sampleRateMessage = 'The MP3 file has a sample rate of sampleRateHz, must have a minimum of minSampleRateHz.';

    public $minBitRate = 0;

    public $minSampleRate = 0;

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }
}
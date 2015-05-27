<?php

namespace MusicBundle\Service;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class AudioProcessor
{
    public function process($input, $output, $time, $fadeTime, $watermark, $watermarkTime)
    {
        if (!file_exists($watermark)) {
            throw new FileNotFoundException($watermark);
        }

        exec(sprintf(
            'sox -m --combine mix-power \'|sox "%s" -p pad %d\' "%s" "%s" fade %d %d %d',
            $watermark,
            $watermarkTime,
            $input,
            $output,
            $fadeTime,
            $time,
            $fadeTime
        ), $o, $returnCode);

        if ($returnCode != 0) {
            throw new \RuntimeException(sprintf('soc return error code: %d, "%s"', $returnCode, implode("\n", $output)));
        }
    }
}
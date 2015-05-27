<?php

namespace MusicBundle\Service;

class AudioProcessor
{
    public function trim($input, $output, $time, $fadeTime = 2)
    {
        exec(sprintf(
            'sox "%s" "%s" fade %d %d %d',
            $input,
            $output,
            $fadeTime,
            $time,
            $fadeTime
        ), $output, $returnCode);

        if ($returnCode != 0) {
            throw new \RuntimeException(sprintf('soc return error code: %d, "%s"', $returnCode, implode("\n", $output)));
        }
    }
}
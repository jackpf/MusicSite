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

        if ($returnCode != 0) {die(var_dump($output,$returnCode));
            throw new \RuntimeException(sprintf('soc return error code: %d, "%s"', implode("\n", $returnCode), $output));
        }
    }
}
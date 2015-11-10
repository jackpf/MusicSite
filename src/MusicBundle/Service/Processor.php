<?php

namespace MusicBundle\Service;

abstract class Processor
{
    protected static function run($cmd)
    {
        $o = [];
        exec($cmd . ' 2>&1', $o, $returnCode);

        if ($returnCode != 0) {
            throw new \RuntimeException(sprintf('"%s" returned error code: %d. "%s"', $cmd, $returnCode, implode("\n", $o)));
        }

        return $returnCode;
    }
}
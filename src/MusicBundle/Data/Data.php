<?php

namespace MusicBundle\Data;

use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\Yaml\Parser;

class Data
{
    public static function getUploadPath()
    {
        $parser = new Parser();

        // Bit hacky...
        $parameters = $parser->parse(file_get_contents(getcwd() . '/../app/config/parameters.yml'))['parameters'];

        if (!isset($parameters['upload_path'])) {
            throw new ParameterNotFoundException('upload_path');
        }

        return $parameters['upload_path'];
    }
}
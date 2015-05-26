<?php

namespace MusicBundle\Data;

use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\Yaml\Parser;

class Data
{
    private static $uploadPath = null;

    public static function getUploadPath()
    {
        if (self::$uploadPath == null) {
            $parser = new Parser();

            // Bit hacky...
            $parameters = $parser->parse(file_get_contents(getcwd() . '/../app/config/parameters.yml'))['parameters'];

            if (!isset($parameters['upload_path'])) {
                throw new ParameterNotFoundException('upload_path');
            }

            self::$uploadPath = $parameters['upload_path'];
        }

        return self::$uploadPath;
    }
}
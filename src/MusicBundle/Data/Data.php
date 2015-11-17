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
            $paths = [
                getcwd() . '/app/config/parameters.yml',
                getcwd() . '/../app/config/parameters.yml'
            ];
            foreach ($paths as $path) {
                if (file_exists($path)) {
                    $parametersPath = $path;
                    break;
                }
            }
            if (!isset($parametersPath)) {
                throw new \RuntimeException('Unable to locate parameters.yml from ' . getcwd());
            }
            $parameters = $parser->parse(file_get_contents($parametersPath))['parameters'];
            if (!isset($parameters['upload_path'])) {
                throw new ParameterNotFoundException('upload_path');
            }
            self::$uploadPath = $parameters['upload_path'];
        }
        return self::$uploadPath;
    }
}
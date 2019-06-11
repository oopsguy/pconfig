<?php

namespace pconfig;

use Exception;
use pconfig\provider\impl\FileProvider;
use pconfig\serializer\ISerializer;
use pconfig\utils\ClassUtil;

/**
 * Configuration Helper
 * Class ConfigHelper
 * @package pconfig
 */
class ConfigHelper
{

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * @param $file
     * @return Config
     * @throws Exception
     */
    public static function read($file)
    {
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $fileName = ucfirst(strtolower($extension)) . 'Serializer';
        $parser = ClassUtil::loadClass("pconfig\\serializer\\impl\\{$fileName}");
        if (!$parser instanceof ISerializer) {
            throw new Exception("Class {$fileName} is NOT a ISerializer!");
        }
        return new Config(
            $parser,
            new FileProvider($file)
        );
    }

}
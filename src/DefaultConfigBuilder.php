<?php

namespace pconfig;

use pconfig\parser\IParser;
use pconfig\provider\impl\FileProvider;
use pconfig\utils\ClassUtil;

/**
 * 默认配置文件类
 * @package pconfig
 * @author Oopsguy <oopsguy@foxmail.com>
 */
class DefaultConfigBuilder
{

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function build($file, array $extraConfig = [])
    {
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $fileName = ucfirst(strtolower($extension)) . 'Parser';
        $filePath = __DIR__ . DIRECTORY_SEPARATOR . 'parser' . DIRECTORY_SEPARATOR . 'impl' . DIRECTORY_SEPARATOR . $fileName . '.php';

        if (!file_exists($filePath)) {
            throw new \Exception("Configuration file parser [{$fileName}] dose NOT found!");
        }

        $parser = ClassUtil::loadClass("pconfig\\parser\\impl\\{$fileName}");

        if (!$parser instanceof IParser) {
            throw new \Exception("Class {$fileName} is NOT a Parser!");
        }

        return new Config(
            $parser,
            new FileProvider([
                'file' => $file
            ]),
            $extraConfig
        );
    }

}
<?php

namespace test\config;

use oopsguy\config\Configuration;

/**
 * 配置工厂
 * Class ConfigFactory
 * @package oopsguy\config
 * @author Oopsguy <4746608426@qq.com>
 */
final class ConfigFactory
{

    private static $config = [
        'provider' => [
            'type' => 'file',
            'params' => [
            ]
        ],
        'parser' => 'array'
    ];

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function getInstance(array $config = [])
    {
        $config = array_merge(self::$config, $config);

        if (!is_array($config['provider'])) {
            $config['provider'] = [
                'type' => $config['provider'],
                'params' => []
            ];
        }

        if (strstr($config['provider']['type'], "\\") === false) {
            $config['provider']['type'] = 'oopsguy\\config\\provider\\impl\\' . ucfirst($config['provider']['type']) . 'Provider';
        }

        if (strstr($config['parser'], "\\") === false) {
            $config['parser'] = 'oopsguy\\config\\parser\\impl\\' . ucfirst($config['parser']) . 'Parser';
        }

        $provider = self::loadClass($config['provider']['type'], $config['provider']['params']);
        $parser = self::loadClass($config['parser']);

        return new Configuration($parser, $provider);
    }

    /**
     * 实例化类
     * @param string $clazz 类名，包含命名空间
     * @param array $args 构造参数
     * @return object 示例
     * @throws \Exception
     */
    private static function loadClass($clazz, array $args = [])
    {
        if (!class_exists($clazz))
            throw new \Exception("Class [$clazz] NOT found!");

        $refection = new \ReflectionClass($clazz);

        if (empty($args)) {
            return $refection->newInstance();
        } else {
            return $refection->newInstanceArgs([$args]);
        }
    }

}
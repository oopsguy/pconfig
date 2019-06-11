<?php

namespace pconfig\utils;

use Exception;
use ReflectionClass;

/**
 * Class ClassUtil
 * @package pconfig\utils
 */
class ClassUtil
{
    /**
     * @param $clazz
     * @param array $args
     * @return object
     * @throws Exception
     */
    public static function loadClass($clazz, array $args = [])
    {
        if (!class_exists($clazz))
            throw new Exception("Class [$clazz] could NOT be found!");

        $refection = new ReflectionClass($clazz);

        if (empty($args)) {
            return $refection->newInstance();
        } else {
            return $refection->newInstanceArgs([$args]);
        }
    }
}
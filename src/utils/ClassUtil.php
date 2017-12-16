<?php

namespace pconfig\utils;

/**
 * Class ClassUtil
 * @package pconfig\utils
 * @author Oopsguy <oopsguy@foxmail.com>
 */
class ClassUtil
{
    public static function loadClass($clazz, array $args = [])
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
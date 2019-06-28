<?php

namespace pconfig\utils;

/**
 * Array utility
 * Class ArrayUtil
 * @package pconfig\utils
 */
class ArrayUtil
{

    /**
     * Return true if the specified array `$arr` has array type value.
     * @param array $arr array
     * @return bool
     */
    public static function hasArrayValue(array $arr)
    {
        $val = array_filter($arr, function ($item) {
            return is_array($item);
        });

        return !empty($val);
    }

    /**
     * Return true if the specified array `$arr` is a assoc array.
     * @param array $arr array
     * @return bool
     */
    public static function isAssocArray(array $arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

}
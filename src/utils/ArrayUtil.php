<?php

namespace oopsguy\utils;

/**
 * 数组相关工具方法
 * Class ArrayUtil
 * @package oopsguy\utils
 * @author Oopsguy <474608426@qq.com>
 */
class ArrayUtil
{

    /**
     * 判断一直数组中是否存在数组元素
     * @param array $arr 目标数组
     * @return bool 是否存在
     */
    public static function hasArrayValue(array $arr)
    {
        $val = array_filter($arr, function($item) {
            return is_array($item);
        });

        return !empty($val);
    }

    /**
     * 判断数组是否是关联数组
     * @param array $arr 目标数组
     * @return bool 是否是关联数组
     */
    public static function isAssocArray(array $arr) {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

}
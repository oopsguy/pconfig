<?php

namespace pconfig\serializer\impl;

use pconfig\serializer\ISerializer;
use pconfig\utils\ArrayUtil;

/**
 * INI配置文件解析类
 * Class INISerializer
 * @package pconfig\serializer\impl
 */
class INISerializer implements ISerializer
{

    /**
     * 配置项层次分割符
     */
    const CONFIG_SEPARATOR = '.';

    /**
     * 解析文本内容
     * @param string $content 文本内容
     * @return array 解析后的数据
     */
    function deserialize($content)
    {
        $content = parse_ini_string($content, true);
        $content = $this->splitArrayKey($content);

        return $content;
    }

    /**
     * 将数据反生成文本内容
     * @param array $data 数据
     * @return string 文本内容
     */
    function serialize($data)
    {
        return $this->arrayToINI($data);
    }

    /**
     * 分割数组键部分，形成层次结构
     * @param array $arr 目标数组
     * @return array 处理后的数组
     */
    private function splitArrayKey(array $arr)
    {
        foreach ($arr as $key => $value) {
            if (strpos($key, self::CONFIG_SEPARATOR) === false)
                continue;
            $subArr = [];
            $this->combineKeyArray(
                $subArr,
                explode(self::CONFIG_SEPARATOR, $key),
                $value
            );
            $arr = array_merge_recursive($arr, $subArr);
            unset($arr[$key]);
        }

        return $arr;
    }

    /**
     * 将数组键部分内容处理成层次结构
     * @param array $array 处理的数组
     * @param array $keys 分割后的key数组
     * @param $value mixed 最终的值
     */
    private function combineKeyArray(array &$array, array $keys, $value)
    {
        $key = array_shift($keys);
        $array[$key] = [];

        if (count($keys) == 0) {
            $array[$key] = $value;
            return;
        }

        $this->combineKeyArray($array[$key], $keys, $value);
    }

    /**
     * 将数组转换成INI文本
     * 参照 StackOverflow
     * @link http://stackoverflow.com/questions/17316873/php-array-to-a-ini-file
     * @param array $a 目标数组
     * @param array $parent 上一层次数组键值
     * @return string 转换后的文本
     */
    private function arrayToINI(array $a, array $parent = [])
    {
        $out = '';
        foreach ($a as $k => $v) {
            if (is_array($v)) {
                //subsection case
                //merge all the sections into one array...
                $sec = array_merge((array)$parent, (array)$k);
                //如果子层元素存在数组元素，则忽略section部分
                if (!ArrayUtil::hasArrayValue($v)) {
                    //add section information to the output
                    $out .= '[' . join(self::CONFIG_SEPARATOR, $sec) . ']' . PHP_EOL;
                }
                //recursively traverse deeper
                $out .= $this->arrayToINI($v, $sec);
            } else {
                //plain key->value case
                $out .= "$k=$v" . PHP_EOL;
            }
        }

        return $out;
    }


}
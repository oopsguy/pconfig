<?php

namespace pconfig\parser\impl;

use pconfig\parser\IParser;
use pconfig\utils\ArrayUtil;

/**
 * 数组配置文件解析类
 * Class PhpParser
 * @package pconfig\parser\impl\PhpParser
 * @author Oopsguy <oopsguy@foxmail.com>
 */
class PhpParser implements IParser
{

    /**
     * 解析文本内容
     * @param string $content 文本内容
     * @return array 解析后的数据
     */
    function parse($content)
    {
        return (array)$content;
    }

    /**
     * 将数据反生成文本内容
     * @param array $data 数据
     * @return string 文本内容
     */
    function unParse($data)
    {
        return '<?php ' . PHP_EOL . 'return ' . var_export($data, true) . '; ';
    }

    /**
     * 将PHP数组转成字符串形式的代码
     * @param array $arr 目标数组
     * @param string $code 转换后的代码字符串
     */
    private function arrayToCode(array $arr, &$code = '')
    {
        $isAssoc = ArrayUtil::isAssocArray($arr);

        foreach ($arr as $key => $value) {
            if ($isAssoc) {
                $code .= "'{$key}' => ";
            }

            if (is_array($value)) {
                $code .= '[' . PHP_EOL;
                $this->arrayToCode($value, $code);
                $code .= '],' . PHP_EOL;
            } elseif (is_numeric($value)) {
                $code .= $value . ',' . PHP_EOL;
            } elseif (is_bool($value)) {
                $code .= ($value ? 'true' : 'false') . ',' . PHP_EOL;
            } elseif (is_null($value)) {
                $code .= 'null,' . PHP_EOL;
            } else {
                $code .= "'{$value}'," . PHP_EOL;
            }
        }
    }


}
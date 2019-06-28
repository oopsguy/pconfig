<?php

namespace pconfig\serializer\impl;

use pconfig\serializer\ISerializer;
use pconfig\utils\ArrayUtil;
use pconfig\utils\StringUtil;

/**
 * 数组配置文件解析类
 * Class PHPSerializer
 * @package pconfig\serializer\impl\PHPSerializer
 */
class PHPSerializer implements ISerializer
{

    /**
     * 解析文本内容
     * @param string $content 文本内容
     * @return array 解析后的数据
     */
    function deserialize($content)
    {
        $content = trim($content);
        $open = '<?php';
        $close = '?>';
        if (StringUtil::startsWith($content, $open)) {
            $content = substr($content, strlen($open));
        }
        if (StringUtil::endsWith($content, $close)) {
            $content = substr($content, 0, strlen($content) - strlen($close));
        }
        return eval($content);
    }

    /**
     * 将数据反生成文本内容
     * @param array $data 数据
     * @return string 文本内容
     */
    function serialize($data)
    {
        //return '<?php ' . PHP_EOL . 'return ' . var_export($data, true) . '; ';
        $this->arrayToCode($data, $code);
        return '<?php ' . PHP_EOL . 'return [' . $code . ']; ';
    }

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
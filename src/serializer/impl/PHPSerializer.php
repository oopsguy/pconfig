<?php

namespace pconfig\serializer\impl;

use pconfig\serializer\ISerializer;

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
        return (array)$content;
    }

    /**
     * 将数据反生成文本内容
     * @param array $data 数据
     * @return string 文本内容
     */
    function serialize($data)
    {
        return '<?php ' . PHP_EOL . 'return ' . var_export($data, true) . '; ';
    }

}
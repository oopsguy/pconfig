<?php

namespace pconfig\serializer\impl;

use pconfig\serializer\ISerializer;

/**
 * JSON配置文件解析类
 * Class JSONSerializer
 * @package pconfig\serializer\impl
 */
class JSONSerializer implements ISerializer
{
    /**
     * 解析文本内容
     * @param string $content 文本内容
     * @return array 解析后的数据
     */
    function deserialize($content)
    {
        return json_decode($content, true);
    }

    /**
     * 将数据反生成文本内容
     * @param array $data 数据
     * @return string 文本内容
     */
    function serialize($data)
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
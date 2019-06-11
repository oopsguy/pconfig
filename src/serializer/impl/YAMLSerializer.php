<?php

namespace pconfig\serializer\impl;

use Spyc;
use pconfig\serializer\ISerializer;

/**
 * YAML配置文件解析类
 * Class YAMLSerializer
 * @package pconfig\serializer\impl
 */
class YAMLSerializer implements ISerializer
{

    /**
     * 解析文本内容
     * @param string $content 文本内容
     * @return array 解析后的数据
     */
    function deserialize($content)
    {
        return Spyc::YAMLLoadString($content);
    }

    /**
     * 将数据反生成文本内容
     * @param array $data 数据
     * @return string 文本内容
     */
    function serialize($data)
    {
        return Spyc::YAMLDump($data);
    }
}
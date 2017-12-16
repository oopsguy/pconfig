<?php

namespace pconfig\parser\impl;

use pconfig\parser\IParser;

/**
 * YAML配置文件解析类
 * Class YamlParser
 * @package pconfig\parser\impl
 * @author Oopsguy <oopsguy@foxmail.com>
 */
class YamlParser implements IParser
{

    /**
     * 解析文本内容
     * @param string $content 文本内容
     * @return array 解析后的数据
     */
    function parse($content)
    {
        return \Spyc::YAMLLoadString($content);
    }

    /**
     * 将数据反生成文本内容
     * @param array $data 数据
     * @return string 文本内容
     */
    function unParse($data)
    {
        return \Spyc::YAMLDump($data);
    }
}
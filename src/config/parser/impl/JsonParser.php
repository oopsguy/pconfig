<?php

namespace oopsguy\config\parser\impl;

use oopsguy\config\parser\IParser;

/**
 * JSON配置文件解析类
 * Class JsonParser
 * @package oopsguy\config\parser\impl
 * @author Oopsguy <474608426@qq.com>
 */
class JsonParser implements IParser
{
    /**
     * 解析文本内容
     * @param string $content 文本内容
     * @return array 解析后的数据
     */
    function parse($content)
    {
        return json_decode($content, true);
    }

    /**
     * 将数据反生成文本内容
     * @param array $data 数据
     * @return string 文本内容
     */
    function unParse($data)
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
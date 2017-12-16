<?php

namespace pconfig\parser;

/**
 * 配置文件解析接口
 * @package pconfig\parser
 * Interface IParser
 * @author Oopsguy <oopsguy@foxmail.com>
 */
interface IParser
{

    /**
     * 解析文本内容
     * @param string $content 文本内容
     * @return array 解析后的数据
     */
    function parse($content);

    /**
     * 将数据反生成文本内容
     * @param array $data 数据
     * @return string 文本内容
     */
    function unParse($data);

}
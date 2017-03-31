<?php

namespace oopsguy\config\parser\impl;

use oopsguy\config\parser\IParser;
use oopsguy\utils\ArrayUtil;

/**
 * XML格式配置文件解析类
 * Class XmlParser
 * @package oopsguy\config\parser\impl
 * @author Oopsguy <474608426@qq.com>
 */
class XmlParser implements IParser
{

    /**
     * 解析文本内容
     * @param string $content 文本内容
     * @return array 解析后的数据
     */
    function parse($content)
    {
        $xml = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA);

        return json_decode(json_encode($xml), true);
    }

    /**
     * 将数据反生成文本内容
     * @param array $data 数据
     * @return string 文本内容
     */
    function unParse($data)
    {
        return '<root>' . $this->arrayToXML($data) . '</root>';
    }

    /**
     * 将数组部分转换成XML格式文本
     * @param array $elem 需要处理的数组
     * @param string $parentElem 父级键名
     * @return string 转换后的XML文本
     */
    private function arrayToXML(array $elem, $parentElem = '')
    {
        if (empty($elem)) {
            return '';
        }

        $xml = '';
        //是否是元素中没有数组元素且是不是关联数组,即xml的末尾节点
        $isEndElem = !ArrayUtil::hasArrayValue($elem) && !ArrayUtil::isAssocArray($elem);

        foreach ($elem as $key => $value) {
            if (is_array($value)) {
                $xml .=  "<{$key}>" . $this->arrayToXML($value, $key) . "</{$key}>";
            } else {
                $realKey = $isEndElem ? $parentElem : $key;
                if (is_numeric($value)) {
                    $xml .= "<{$realKey}>{$value}</{$realKey}>";
                } else {
                    $xml .= "<{$realKey}>" . htmlspecialchars($value) . "</{$realKey}>";
                }
            }
        }

        return $xml;
    }
}
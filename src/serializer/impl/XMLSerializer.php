<?php

namespace pconfig\serializer\impl;

use pconfig\serializer\ISerializer;
use pconfig\utils\ArrayUtil;

/**
 * XML格式配置文件解析类
 * Class XMLSerializer
 * @package pconfig\serializer\impl
 */
class XMLSerializer implements ISerializer
{

    private $config = [
        'root' => 'root'
    ];

    public function __construct(array $config = [])
    {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * 解析文本内容
     * @param string $content 文本内容
     * @return array 解析后的数据
     */
    function deserialize($content)
    {
        $xml = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA);

        return json_decode(json_encode($xml), true);
    }

    /**
     * 将数据反生成文本内容
     * @param array $data 数据
     * @return string 文本内容
     */
    function serialize($data)
    {
        return '<?xml version="1.0" encoding="UTF-8"?>' . "<{$this->config['root']}>" . PHP_EOL . $this->arrayToXML($data) . "</{$this->config['root']}>";
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
                if (ArrayUtil::isAssocArray($value)) {
                    $xml .= "<{$key}>" . PHP_EOL . $this->arrayToXML($value, $key) . "</{$key}>" . PHP_EOL;
                } else {
                    $xml .= $this->arrayToXML($value, $key);
                }
            } else {
                $realKey = $isEndElem ? $parentElem : $key;
                if (is_numeric($value)) {
                    $xml .= "<{$realKey}>{$value}</{$realKey}>" . PHP_EOL;
                } else {
                    $xml .= "<{$realKey}>" . htmlspecialchars($value) . "</{$realKey}>" . PHP_EOL;
                }
            }
        }

        return $xml;
    }
}
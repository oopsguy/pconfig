<?php

namespace oopsguy\config;

use oopsguy\config\parser\IParser;
use oopsguy\config\provider\AbstractProvider;

/**
 * 配置文件类
 * @package oopsguy\config
 * @author Oopsguy <474608426@qq.com>
 */
class Configuration implements \ArrayAccess
{
    /**
     * 默认配置项下标分割符
     */
    const CONFIG_SEPARATOR = 'separator';

    /**
     * 额外配置处理配置项名称大小写
     */
    const CONFIG_KEY_CASE = 'key_case';

    /**
     * 处理配置项名称统一大写
     */
    const VALUE_CASE_UPPER = CASE_UPPER;

    /**
     * 处理配置项名称统一小写
     */
    const VALUE_CASE_LOWER = CASE_LOWER;

    /**
     * 元素不存在时中断寻找
     */
    const EXISTS_MODE_BREAK = 0;

    /**
     * 元素不存在时追加空数组
     */
    const EXISTS_MODE_APPEND = 1;

    /**
     * @var array 配置内容
     */
    private $config = [];

    /**
     * @var array 附加配置
     */
    private $extraConfig = [
        self::CONFIG_SEPARATOR => '.',
    ];

    /**
     * @var IParser 配置文件内容解析器
     */
    private $parser;

    /**
     * @var AbstractProvider 配置文件内容读取对象
     */
    private $provider;

    /**
     * 构造方法
     * @param IParser $parser 内容解析器
     * @param AbstractProvider $provider 内容提供者
     * @param array $extraConfig 解析类配置
     * @throws \Exception
     */
    function __construct(IParser $parser, AbstractProvider $provider, array $extraConfig = [])
    {
        if (is_null($parser) || is_null($provider)) {
            throw new \Exception('Parser and Provider can NOT be NULL!');
        }

        $this->parser = $parser;
        $this->provider = $provider;

        $content = $this->provider->read();
        $this->config = $this->parser->parse($content);

        //配置项名称大小写处理
        if (isset($this->extraConfig[self::CONFIG_KEY_CASE])) {
            $this->config = array_change_key_case($this->config, $this->extraConfig[self::CONFIG_KEY_CASE]);
        }
    }

    /**
     * 默认持久化到文件中
     * @return bool
     */
    public function save()
    {
        $content = $this->parser->unParse($this->config);

        return $this->provider->save($content);
    }

    /**
     * 设置配置文件
     * @param string $file 文件路径
     */
    public function setConfigFile($file)
    {
        $this->provider->setConfig('file', $file);
    }

    /**
     * 获取配置项
     * @param null $key 配置项名称，可以获取多层次配置，层次之间用配置分割符分割
     * @return array|null 配置项值
     */
    public function get($key = null)
    {
        if ($key == null)
            return $this->config;

        if (empty($key))
            return [];

        $keys = $this->parseKey($key);
        $value = null;

        $this->find($this->config, $keys, function ($item) use (&$value) {
            $value = $item;
        });

        return $value;
    }

    /**
     * 删除配置项
     * @param null $key $key 配置项名称，可以获取多层次配置，层次之间用配置分割符分割
     * @return bool 是否删除成功
     */
    public function delete($key = null)
    {
        if ($key == null) {
            $this->config = [];
            return true;
        }

        if (empty($key))
            return false;

        $keys = $this->parseKey($key);

        return $this->find($this->config, $keys, function ($item, &$config, $index) {
            unset($config[$index]);
        });
    }

    /**
     * 设置配置
     * @param $key $key 配置项名称，可以获取多层次配置，层次之间用配置分割符分割
     * @param $value 配置的值
     * @return bool 是否设置成功
     */
    public function set($key, $value)
    {
        if (empty($key))
            return false;

        $keys = $this->parseKey($key);

        return $this->find($this->config, $keys, function (&$item) use ($value) {
            $item = $value;
        }, self::EXISTS_MODE_APPEND);
    }

    /**
     * 获取解析类配置信息
     * @param null $key 配置项，null则获取全部
     * @return array|mixed|null 配置内容
     */
    public function getConfig($key = null)
    {
        if ($key == null)
            return $this->extraConfig;

        return isset($this->extraConfig[$key]) ? $this->extraConfig[$key] : null;
    }

    /**
     * 设置解析配置
     * @param string|array $key 配置项名称
     * @param mixed $value 配置值
     */
    public function setConfig($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                isset($this->config[$k]) && $this->config[$k] = $v;
            }
        } else {
            isset($this->config[$key]) && $this->config[$key] = $value;
        }
    }

    /**
     * SPL接口，判断是否存在指定配置项
     * @param mixed $offset 配置项名称
     * @return bool 是否存在
     */
    public function offsetExists($offset)
    {
        $keys = $this->parseKey($offset);

        return $this->find($this->config, $keys);
    }

    /**
     * SPL接口，获取配置值，同get方法
     * @param mixed $offset 配置项名称
     * @return array|null 配置值
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * SPL接口，设置配置，同set方法
     * @param mixed $offset 配置项名称
     * @param mixed $value 配置值
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * SPL接口，删除配置，同delete方法
     * @param mixed $offset 配置项名称
     */
    public function offsetUnset($offset)
    {
        $this->delete($offset);
    }

    /**
     * 分割处理配置项名称
     * @param $key 配置项名称
     * @return array 分割后的数组
     */
    private function parseKey($key)
    {
        return explode($this->extraConfig[self::CONFIG_SEPARATOR], $key);
    }

    /**
     * 寻找目标配置项
     * @param array $config 配置数组
     * @param array $keys $key 配置项下标数组
     * @param \Closure|null $callback 处理回调
     * @param int $existsMode 元素不能存在的时候的处理方式
     * @param int $index 当前配置下标的位置
     * @return bool 是否找到配置项
     */
    private function find(array &$config, array $keys, \Closure $callback = null, $existsMode=self::EXISTS_MODE_BREAK, $index = 0)
    {
        if(!isset($config[$keys[$index]])) {
            if ($existsMode == self::EXISTS_MODE_BREAK) {
                return false;
            }

            $config[$keys[$index]] = [];
        }

        if (count($keys) - 1 == $index) {
            is_callable($callback) && $callback($config[$keys[$index]], $config, $keys[$index]);

            return true;
        }

        if (is_array($config[$keys[$index]])) {
            return $this->find($config[$keys[$index]], $keys, $callback, $existsMode, $index + 1);
        }

        return false;
    }
}
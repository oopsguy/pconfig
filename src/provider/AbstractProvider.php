<?php

namespace pconfig\provider;

/**
 * 配置文件内容操作类
 * Class AbstractProvider
 * @package pconfig\provider
 * @author Oopsguy <oopsguy@foxmail.com>
 */
abstract class AbstractProvider
{
    /**
     * @var array 配置数据
     */
    protected $config = [];

    public function __construct(array $config = [])
    {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * 读取配置内容
     * @return mixed 配置内容
     */
    abstract function read();

    /**
     * 配置数据持久化
     * @param mixed $data 配置数据
     * @return boolean 是否成功
     */
    abstract function save($data);

    /**
     * 设置配置
     * @param array|string $key 配置项名称
     * @param mixed $value 配置值
     */
    public function setConfig($key, $value = null)
    {
        if (empty($key))
            return;

        if (is_array($key)) {
            $this->config = array_merge($this->config, $key);
        } else {
            $this->config[$key] = $value;
        }
    }

    /**
     * 获取配置
     * @param string $key 配置项名称
     * @return mixed 配置值
     */
    public function getConfig($key)
    {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        }

        return null;
    }
}
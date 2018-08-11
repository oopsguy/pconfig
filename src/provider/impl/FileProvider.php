<?php

namespace pconfig\provider\impl;

use pconfig\provider\AbstractProvider;

/**
 * 文件型配置内容操作类
 * Provide configuration data from a file
 * Class FileProvider
 * @package pconfig\provider\impl
 * @author Oopsguy <oopsguy@foxmail.com>
 */
class FileProvider extends AbstractProvider
{

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        if (!isset($this->config['file']) || empty($this->config['file'])) {
            throw new \Exception("Config 'file' must be required ");
        }

        $this->config['file'] = trim($this->config['file']);
    }

    /**
     * 读取配置内容
     * Read data from file
     * @return mixed 配置内容
     */
    function read()
    {
        if (!file_exists($this->config['file'])) {
            return '';
        }

        $phpExt = '.php';

        //如果文件扩展名是php，则用include包含引入，内容代码会自动解析，不再走文本内容
        if (substr_compare(strtolower($this->config['file']), $phpExt, -strlen($phpExt)) === 0) {
            /** @noinspection PhpIncludeInspection */
            return include $this->config['file'];
        }

        return file_get_contents($this->config['file']);
    }

    /**
     * 配置数据持久化
     * Write data to file
     * @param mixed $data 配置数据
     * @return boolean 是否成功
     */
    function save($data)
    {
        return file_put_contents($this->config['file'], $data) !== false;
    }
}
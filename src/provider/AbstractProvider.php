<?php

namespace pconfig\provider;

/**
 * 配置文件内容操作类
 * Class AbstractProvider
 * @package pconfig\provider
 */
abstract class AbstractProvider
{

    /**
     * Read configuration
     * @return mixed configuration array
     */
    abstract function read();

    /**
     * Persist configuration
     * @param mixed $config configuration data
     * @return boolean success or not
     */
    abstract function save($config);

}
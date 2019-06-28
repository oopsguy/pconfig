<?php

namespace pconfig\provider;

/**
 * Class IProvider
 * @package pconfig\provider
 */
interface IProvider
{
    /**
     * Read configuration
     * @return mixed configuration array
     */
    function read();

    /**
     * Persist configuration
     * @param mixed $config configuration data
     * @return boolean success or not
     */
    function save($config);
}
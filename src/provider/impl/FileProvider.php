<?php

namespace pconfig\provider\impl;

use Exception;
use pconfig\provider\IProvider;

/**
 * FileProvider provides configuration data from file
 * Class FileProvider
 * @package pconfig\provider\impl
 */
class FileProvider implements IProvider
{
    /**
     * @var string configuration file path
     */
    protected $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Read data from file
     * @param Exception
     * @return mixed configuration data
     * @throws Exception
     */
    function read()
    {
        return file_get_contents($this->file);
    }

    /**
     * Write data to file
     * @param mixed $data configuration data
     * @return boolean success or not
     */
    function save($data)
    {
        return file_put_contents($this->file, $data) !== false;
    }

    /**
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

}
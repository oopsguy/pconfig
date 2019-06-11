<?php

namespace pconfig\provider\impl;

use pconfig\provider\AbstractProvider;

/**
 * FileProvider provides configuration data from file
 * Class FileProvider
 * @package pconfig\provider\impl
 */
class FileProvider extends AbstractProvider
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
     * @return mixed configuration data
     */
    function read()
    {
        if (!file_exists($this->file)) {
            return '';
        }
        $phpExt = '.php';
        // If the extension is php, include the file directly.
        if (substr_compare(strtolower($this->file), $phpExt, -strlen($phpExt)) === 0) {
            /** @noinspection PhpIncludeInspection */
            return include $this->file;
        }
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
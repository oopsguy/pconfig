<?php

namespace pconfig\provider\impl;

use pconfig\provider\IProvider;
use PHPUnit\Framework\TestCase;

class FileProviderTest extends TestCase
{
    /**
     * @var IProvider
     */
    private $provider;

    protected function setUp()
    {
        $this->provider = new FileProvider(__DIR__ . '/data/data.txt');
    }

    public function testSave()
    {
        $this->assertTrue($this->provider->save(time()), 'save failed');
    }

    public function testRead()
    {
        $this->assertNotEmpty($this->provider->read(), 'read failed');
    }

}
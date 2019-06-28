<?php

namespace pconfig;

use Exception;
use PHPUnit\Framework\TestCase;

class PConfigTest extends TestCase
{
    private $basePath = __DIR__ . '/data/';

    public function testJSON()
    {
        $this->testConfig('json');
    }

    public function testINI()
    {
        $this->testConfig('ini');
    }

    public function testPHP()
    {
        $this->testConfig('php');
    }

    public function testYAML()
    {
        $this->testConfig('yaml');
    }

    private function testConfig($type)
    {
        $config = null;
        try {
            $config = new PConfig($this->basePath . 'config.' . $type);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
        $this->assertNotNull($config);

        $this->assertArrayHasKey('language', $config);
        $this->assertArrayHasKey("php", $config->get('language'));
        $this->assertArrayHasKey("php", $config['language']);
        $this->assertArrayHasKey("type", $config['language']['php']);
        $this->assertCount(3, $config['language']['php']['type']);
        $this->assertContains('object', $config['language']['php']['type']);

        try {
            $config = new PConfig([
                'file' => $this->basePath . 'config.' . $type,
            ]);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
        $this->assertNotNull($config);

        $this->assertArrayHasKey('language', $config);
        $this->assertArrayHasKey("php", $config->get('language'));
        $this->assertArrayHasKey("php", $config['language']);
        $this->assertArrayHasKey("type", $config['language']['php']);
        $this->assertCount(3, $config['language']['php']['type']);
        $this->assertContains('object', $config['language']['php']['type']);

        $this->assertTrue($config->set('hello.world', true));

        $this->assertArrayHasKey('hello', $config);
        $this->assertArrayHasKey('world', $config->get('hello'));
        $this->assertArrayHasKey('world', $config['hello']);

        $this->assertTrue($config->exists('hello'));
        $this->assertTrue(isset($config['hello']));
        $this->assertTrue($config->exists('hello.world'));
        $this->assertTrue(isset($config['hello.world']));
        $this->assertTrue(isset($config['hello']['world']));

        $this->assertNotEquals('true', $config->get('hello.world'));
        $this->assertNotEquals('true', $config['hello.world']);
        $this->assertNotEquals('true', $config['hello']['world']);

        $this->assertEquals(true, $config->get('hello.world'));
        $this->assertEquals(true, $config['hello.world']);
        $this->assertEquals(true, $config['hello']['world']);

        $this->assertFalse($config->delete('hello.world1'));
        $this->assertTrue($config->delete('hello.world'));

        unset($config['hello.world']);

        $this->assertFalse($config->exists('hello.world'));
        $this->assertFalse(isset($config['hello.world']));
        $this->assertNull($config['hello.world']);

        $config->set('array.arr', [
            1, 2, 3, 4
        ]);

        $this->assertCount(4, $config->get('array.arr'));
        $this->assertCount(4, $config['array.arr']);
        $this->assertEquals(1, $config['array.arr.0']);
        $this->assertEquals(4, $config->get('array.arr.3'));

        $config->setConfig([
            PConfig::CONFIG_KEY_EXTRACT_SEPARATOR => '@'
        ]);

        $this->assertTrue($config->set('my@config', 'myconfig'));
        $this->assertArrayHasKey('config', $config['my']);
        $this->assertEquals('myconfig', $config['my@config']);
        $this->assertEquals('myconfig', $config['my']['config']);

        $this->assertEquals("hello", $config->get('hello.php', 'hello'));

        $config->setFile($this->basePath . 'config_new.' . $type);

        try {
            $this->assertTrue($config->save());
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

}
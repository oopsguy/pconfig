<?php

namespace pconfig\serializer\impl;

use pconfig\serializer\ISerializer;
use PHPUnit\Framework\TestCase;

class PHPSerializerTest extends TestCase
{
    /**
     * @var ISerializer
     */
    private $serializer;

    protected function setUp()
    {
        $this->serializer = new PHPSerializer();
    }

    public function testDeserialize()
    {
        $phpContent = <<<PHP
<?php
return [
    'app' => 'app name',
    'version' => '1.0',
    'debug' => false,
    'settings' => [
        'key' => 'value'
    ]
];
PHP;
        $arr = $this->serializer->deserialize($phpContent);
        $this->assertNotEmpty($arr);
        $this->assertArrayHasKey('app', $arr);
    }

    public function testSerialize()
    {
        $arr = [
            'app' => 'app name',
            'version' => '1.0',
            'debug' => false,
            'settings' => [
                'key' => 'value'
            ]
        ];
        $text = $this->serializer->serialize($arr);
        $this->assertNotEmpty($text);
        $this->assertStringStartsWith('<?php', $text);
        $this->assertContains("'app' => 'app name'", $text);
    }

}
<?php

namespace pconfig\test\serializer\impl;

use pconfig\serializer\ISerializer;
use pconfig\serializer\impl\PHPSerializer;
use pconfig\test\serializer\BaseSerializerTest;

/**
 * Testing for PHPSerializer
 * Class PHPSerializerTest
 * @package pconfig\test\serializer\impl
 */
class PHPSerializerTest extends BaseSerializerTest
{
    /**
     * @return ISerializer
     */
    protected function targetSerializer()
    {
        return new PHPSerializer();
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
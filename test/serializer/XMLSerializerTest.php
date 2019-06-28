<?php

namespace pconfig\test\serializer\impl;

use pconfig\serializer\impl\XMLSerializer;
use pconfig\serializer\ISerializer;
use pconfig\test\serializer\BaseSerializerTest;

/**
 * Testing for XMLSerializer
 * Class XMLSerializerTest
 * @package pconfig\test\serializer\impl
 */
class XMLSerializerTest extends BaseSerializerTest
{
    const ROOT = 'xml';

    /**
     * @return ISerializer
     */
    protected function targetSerializer()
    {
        return new XMLSerializer([
            'root' => self::ROOT
        ]);
    }

    public function testDeserialize()
    {
        $xmlContent = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<xml>
    <title>introduce</title>
    <language>PHP</language>
    <desc>
        <support>
            <databases>
                <db>mysql</db>
                <db>oracle</db>
                <db>redis</db>
                <db>sqlserver</db>
            </databases>
            <files>
                <file>json</file>
                <file>yaml</file>
                <file>xml</file>
                <file>txt</file>
            </files>
        </support>
    </desc>
</xml>
XML;
        $xml = $this->serializer->deserialize($xmlContent);
        $this->assertNotEmpty($xml);
        $this->assertEquals('introduce', $xml['title']);
        $this->assertArrayHasKey('desc', $xml);
        $this->assertArrayHasKey('support', $xml['desc']);
        $this->assertArrayHasKey('files', $xml['desc']['support']);
        $this->assertArrayHasKey('file', $xml['desc']['support']['files']);
        $this->assertCount(4, $xml['desc']['support']['files']['file']);
    }

    public function testSerialize()
    {
        $xml = [
            'programming' => [
                'language' => [
                    'C++',
                    'C#',
                    'Java',
                    'Go'
                ]
            ]
        ];
        $text = $this->serializer->serialize($xml);
        $this->assertNotEmpty($text);
        $this->assertStringStartsWith('<?xml version="1.0" encoding="UTF-8"?>', $text);
        $this->assertStringEndsWith('</' . self::ROOT . '>', $text);
        $this->assertNotContains('<root>', $text);
    }

}
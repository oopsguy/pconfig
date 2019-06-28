<?php

namespace pconfig\test\serializer\impl;

use pconfig\serializer\ISerializer;
use pconfig\serializer\impl\YAMLSerializer;
use pconfig\test\serializer\BaseSerializerTest;

/**
 * Testing for YAMLSerializer
 * Class YAMLSerializerTest
 * @package pconfig\test\serializer\impl
 */
class YAMLSerializerTest extends BaseSerializerTest
{
    /**
     * @return ISerializer
     */
    protected function targetSerializer()
    {
        return new YAMLSerializer();
    }

    public function testDeserialize()
    {
        $yamlContent = <<<YAML
new_post_name: :title.md
default_layout: post
highlight:
  enable: true
  line_number: true

baidusitemap:
  path: baidusitemap.xml

database:
  rdbms:
    - oracle
    - mysql
    - sqlserver
  nosql:
    mongodb
    redis
map:
  a: A
  b: B
  c: C
YAML;
        $yaml = $this->serializer->deserialize($yamlContent);
        $this->assertNotEmpty($yaml);
        $this->assertArrayHasKey('new_post_name', $yaml);
        $this->assertArrayHasKey('database', $yaml);
        $this->assertArrayHasKey('nosql', $yaml['database']);
        $this->assertArraySubset(['path' => 'baidusitemap.xml'], $yaml['baidusitemap']);
        $this->assertTrue($yaml['highlight']['enable']);
        $this->assertEquals('C', $yaml['map']['c']);
    }

    public function testSerialize()
    {
        $yaml = [
            'highlight' => [
                'enable' => true
            ],
            'database' => [
                'rdbms' => [
                    'oracle',
                    'mysql',
                    'sqlserver'
                ]
            ],
            'map' => [
                'a' => 'A',
                'b' => 'B',
                'c' => 'C'
            ]
        ];
        $text = $this->serializer->serialize($yaml);
        $this->assertNotEmpty($text);
        $this->assertContains('- oracle', $text);
    }

}
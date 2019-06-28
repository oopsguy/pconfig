<?php

namespace pconfig\serializer\impl;

use pconfig\serializer\ISerializer;
use PHPUnit\Framework\TestCase;

class JSONSerializerTest extends TestCase
{

    /**
     * @var ISerializer
     */
    private $serializer;

    protected function setUp()
    {
        $this->serializer = new JSONSerializer();
    }

    public function testDeserialize()
    {
        $jsonContent = <<<JSON
{
  "api" : {
    "qiniu": "qiniu-api",
    "aliyun": {
      "kv": [
        "redis",
        "memcached"
      ]
    }
  }
}
JSON;
        $json = $this->serializer->deserialize($jsonContent);
        $this->assertNotEmpty($json);
        $this->assertNotEmpty($json['api']);
        $this->assertNotEmpty($json['api']['aliyun']);
        $this->assertEquals('memcached', $json['api']['aliyun']['kv'][1]);
    }

    public function testSerialize()
    {
        $json = [
            "api" => [
                "qiniu" => "qiniu-api",
                "aliyun" => [
                    "kv" => [
                        "redis",
                        "memcached"
                    ]
                ]
            ]
        ];
        $rawJson = $this->serializer->serialize($json);
        $this->assertNotEmpty($rawJson);

        $arr = json_decode($rawJson, true);
        $this->assertNotEmpty($arr);
        $this->assertNotEmpty($arr['api']);
        $this->assertNotEmpty($arr['api']['aliyun']);
        $this->assertNotEmpty($arr['api']['aliyun']['kv']);
        $this->assertEquals('memcached', $arr['api']['aliyun']['kv'][1]);
    }

}
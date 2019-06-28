<?php

namespace pconfig\test\serializer\impl;

use pconfig\serializer\impl\JSONSerializer;
use pconfig\serializer\ISerializer;
use pconfig\test\serializer\BaseSerializerTest;

/**
 * Testing for JSONSerializer
 * Class JSONSerializerTest
 * @package pconfig\test\serializer\impl
 */
class JSONSerializerTest extends BaseSerializerTest
{
    /**
     * @return ISerializer
     */
    protected function targetSerializer()
    {
        return new JSONSerializer();
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
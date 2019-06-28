<?php

namespace pconfig\test\serializer\impl;

use pconfig\serializer\impl\INISerializer;
use pconfig\serializer\ISerializer;
use pconfig\test\serializer\BaseSerializerTest;

/**
 * Testing for INISerializer
 * Class INISerializerTest
 * @package pconfig\test\serializer\impl
 */
class INISerializerTest extends BaseSerializerTest
{
    /**
     * @return ISerializer
     */
    protected function targetSerializer()
    {
        return new INISerializer();
    }

    public function testDeserialize()
    {
        $iniContent = <<<INI
[oss]
one=qiniu
two=aliyun
[db.rdbms]
db0=mysql
db1=sqlserver
INI;

        $arr = $this->serializer->deserialize($iniContent);
        $this->assertNotEmpty($arr, 'result is empty');
        $this->assertEquals('qiniu', $arr['oss']['one'], 'failed to get `oss.one`');
        $this->assertEquals('sqlserver', $arr['db']['rdbms']['db1'], 'failed to get `db.rdbms.db1`');
        $this->assertCount(2, $arr['db']['rdbms']);
    }

    public function testSerialize()
    {
        $text = $this->serializer->serialize([
            'oss' => [
                'one' => 'qiniu',
                'two' => 'aliyun'
            ],
            'db' => [
                'rdbms' => [
                    'db0' => 'mysql',
                    'db1' => 'sqlserver'
                ]
            ],
            'array' => [1, 2, 3, 4, 5]
        ]);
        $arr = explode(PHP_EOL, $text);
        $this->assertNotEmpty($text, 'Empty reuslt');
        $this->assertEquals('[oss]', $arr[0]);
        $this->assertEquals('[db.rdbms]', $arr[3]);
        $this->assertEquals('sqlserver', explode('=', $arr[5])[1]);
    }

}
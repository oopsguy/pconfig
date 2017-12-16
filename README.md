# PConfig 配置文件库

PConfig 是一个使用了 PHP 编写的配置文件解析库，能够解析 PHP（数组）、JSON、YAML、XML 和 INI 格式的文件，其统一了 API 操作，屏蔽了不同格式文件的解析细节，使用起来更加简单、高效。

PConfig is a PHP library for parsing config file (php, json, xml, yaml, ini).

## 安装 Install

使用 PHP Composer 安装

```bash
composer require oopsguy/pconfig
```

## 用法 Usage

```php
<?php

use pconfig\DefaultConfigBuilder;
use pconfig\Config;
use pconfig\parser\impl\YamlParser;
use pconfig\provider\impl\FileProvider;

// php array
$config = DefaultConfigBuilder::build("config/config.php");
echo $config->get("app");
$config->delete("version");
$config->set('debug', false);
$config->save();

// json file
$jsonConfig = DefaultConfigBuilder::build('config/config.json');
$jsonConfig->set('homepage', 'https://github.com');
$jsonConfig->setConfigFile('config/temp_json.json'); //save as temp_json.json file
$jsonConfig->save();

$parser = new YamlParser();
$provider = new FileProvider(['file' => 'config/settings.yaml']);
$extConfig = new Config($parser, $provider);
$extConfig->set('type', 'yaml');
$extConfig->save();
```

## 配置文件示例 Config file example

### PHP Array

```php
return [
    'app' => 'app name',
    'version' => '1.0',
    'debug' => false,
    'settings' => [
        'key' => 'value'
    ]
]
```

### JSON

```json
{
  "api" : {
    "qiniu": "qiniu -api",
    "aliyun": {
      "db": [
        "redis",
        "memcached",
        "memcache",
        "nosql",
        "rdbms"
      ]
    }
  }
}
```

### INI

```ini
[oss]
key1=qiniu
key2=aliyun
key3=tencent
[db.rdbms]
subkey=mysql
subkey1=sqlserver
subkey3=oracle
subkey4=db2
[db.other.nosql]
a=mongodb
b=redis
c=memcache
d=memcached
[hello.world.1.2]
a=0
b=6
```
### XML

```xml
<root>
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
</root>
```
### YAML 

```yaml
new_post_name: :title.md
default_layout: post
highlight:
  enable: true
  line_number: true

baidusitemap:
  path: baidusitemap.xml

database:
  rdbms:
    oracle
    mysql
    sqlserver
  nosql:
    mongodb
    redis
```

## License

MIT License




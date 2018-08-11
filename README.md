# PConfig 配置文件库

PConfig 是一个使用了 PHP 编写的配置文件解析库，能够解析 PHP（array）、JSON、YAML、XML 和 INI 格式的文件，其统一了 API 操作，屏蔽了不同格式文件的解析细节，使用起来更加简单、高效。

PConfig is a PHP library for parsing config file ( e.g. php, json, xml, yaml, ini...) It has simple apis and  easy to use. You can custom your own provider and parser to process data.

## 安装 Install

使用 PHP Composer 安装

Install by composer

```bash
composer require oopsguy/pconfig:1.0
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
$config->set("settings.key", "new value");
$config->save();

// json file
$jsonConfig = DefaultConfigBuilder::build('config/config.json');
$jsonConfig->set('homepage', 'https://github.com');
// Save as temp_json.json file
$jsonConfig->setPath('config/temp_json.json');
$jsonConfig->save();

$parser = new YamlParser();
$provider = new FileProvider(['file' => 'config/settings.yaml']);
$extConfig = new Config($parser, $provider);
$extConfig->set('type', 'yaml');
$extConfig->save();
```

配置项的层级关系使用逗号分割，您也可以配置自定义分割规则

The hierarchy of configuration items is separated by commas, you can custom your own separator.

```php
<?php
use pconfig\Config;
use pconfig\provider\impl\FileProvider;
use pconfig\parser\impl\JsonParser;

$config = new Config(
    new JsonParser(), // Specify format parser
    new FileProvider(['file' => 'config/config.php']),
    [
        // 配置项统一小写转换
        // Change config keys into case lower
        Config::CONFIG_KEY_CASE => Config::KEY_CASE_LOWER, 
        // 配置项以 . 分割
        // Setting config item separator
        Config::CONFIG_SEPARATOR => '.', 
    ]
);
```

## 下标访问 ArrayAccess

配置实现了 SPL ArrayAccess 接口，支持下标操作方式

Implement SPL ArrayAccess interface.

```php
<?php
use pconfig\DefaultConfigBuilder;

require '../vendor/autoload.php';

// access by index
$json = DefaultConfigBuilder::build('./config/arrayaccess.json');
$json['status'] = true;
$json['data'] = [
    'page' => 1,
    'pageSize' => 10,
    'pages' => 2,
    'total' => 13,
    'list' => [
        [
            'username' => 'oopsguy',
            'gender' => '男'
        ]
    ]
];
$json['msg'] = 'ok';
$json['delData'] = 'XHSYSYSDkoksoada8dsaidsa9d8adsa';

// unset and isset 
var_dump(isset($json['delData']));
unset($json['delData']);
var_dump(isset($json['delData']));

// save config
$json->save();
```

config example

```php
<?php
return [
    'level1' => [
        'level2' => [
            'level3' => 'Level 3'
        ]
    ]
];
```

操作 `level3` 配置项

Set `level3` item

```php
<?php

// init config ...
$config->set('level1.level2.level3', "Level end");
// delete config item
$config->delete('level1.level2');
```

## 配置文件示例 Config file examples

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




# PConfig

PConfig is a PHP library for parsing configuration (php, json, xml, yaml, ini). 
It has simple APIs and is easy to use. 
You can also custom your own provider and serializer.

## Install

```bash
composer require oopsguy/pconfig:1.1
```

## Usage

```php
<?php

use pconfig\Config;
use pconfig\ConfigHelper;
use pconfig\serializer\impl\YAMLSerializer;
use pconfig\provider\impl\FileProvider;

// Parsing PHP array
// Auto detect file extension and choose a suitable serializer
$config = ConfigHelper::read("config/config.php");
echo $config->get("app");
$config->delete("version");
$config->set('debug', false);
$config->set("settings.key", "new value");
$config->save();

// Parsing JSON
$jsonConfig = ConfigHelper::read('config/config.json');
$jsonConfig->set('homepage', 'https://github.com');
// Save as temp.json file
$jsonConfig->setPath('config/temp.json');
$jsonConfig->save();

// Parsing YAML
// Explicitly specify a YAML serializer
$parser = new YAMLSerializer();
$provider = new FileProvider('config/settings.yaml');
$extConfig = new Config($parser, $provider);
$extConfig->set('type', 'yaml');
$extConfig->save();
```

The default key separator is a `.`.

```
key1.key2.key3
```

You can use `Config::CONFIG_SEPARATOR` to custom your own separator.

```
Config::CONFIG_SEPARATOR => '-',
```

```
key1-key2-key3
```

```php
<?php
use pconfig\Config;
use pconfig\provider\impl\FileProvider;
use pconfig\serializer\impl\JSONSerializer;

$config = new Config(
     // Specify the serializer
    new JSONSerializer(),
    new FileProvider('config/config.php'),
    [
        // Change all keys into case lower
        Config::CONFIG_KEY_CASE => Config::KEY_CASE_LOWER, 
        // Set the key separator
        Config::CONFIG_SEPARATOR => '-', 
    ]
);
```

## ArrayAccess

`Config` has implemented `ArrayAccess` interface, you can access configuration by index.

```php
<?php
use pconfig\ConfigHelper;

// Access by index
$json = ConfigHelper::read('./config/arrayaccess.json');
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

// unset and isset API
var_dump(isset($json['delData']));
unset($json['delData']);
var_dump(isset($json['delData']));

// Save to file
$json->save();
```

Nested configuration.

```php
$config->set('level1.level2.level3', "Level end");
$config->delete('level1.level2');
```

## Config file examples

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




# PConfig

PConfig is a PHP library for parsing configuration. 
It has simple APIs and is easy to use.

## Supported formats

- php
- json
- xml
- yaml
- ini

## Installation

```bash
composer require oopsguy/pconfig
```

## Usage

```php
<?php

use pconfig\PConfig;
use pconfig\serializer\impl\YAMLSerializer;
use pconfig\provider\impl\FileProvider;

// Parsing PHP array
// Auto detect file extension and choose a suitable serializer
$config = new PConfig("config/config.php");
echo $config->get("app");
$config->delete("version");
$config->set('debug', false);
$config->set("settings.key", "new value");
$config->save();

// Parsing JSON
$jsonConfig = new PConfig('config/config.json');
$jsonConfig->set('homepage', 'https://github.com');
// Save as temp.json file
$jsonConfig->setFile('config/temp.json');
$jsonConfig->save();

// Parsing YAML
// Explicitly specify a YAML serializer
$serializer = new YAMLSerializer();
$provider = new FileProvider('config/settings.yaml');
$extConfig = new PConfig([
        'provider' => $provider,
        'serializer' => $serializer
    ]);
$extConfig->set('type', 'yaml');
$extConfig->save();
```

The default key separator is a dot-notation `.`.

```
key1.key2.key3
```

You can use `PConfig::CONFIG_KEY_EXTRACT_SEPARATOR` to custom your own separator.

```
PConfig::CONFIG_KEY_EXTRACT_SEPARATOR => '-',
```

```
key1-key2-key3
```

```php
<?php
use pconfig\PConfig;
use pconfig\provider\impl\FileProvider;
use pconfig\serializer\impl\JSONSerializer;

$config = new PConfig([
        // Specify the serializer
        'serializer' => new JSONSerializer(),
        'provider' => new FileProvider('config/config.php'),
        'config' => [
                // Set the key separator
                PConfig::CONFIG_KEY_EXTRACT_SEPARATOR => '-', 
            ]
    ]);
```

## ArrayAccess

`Config` has implemented `ArrayAccess` interface, you can access configuration by index.

```php
<?php
use pconfig\PConfig;

// Access by index
$json = new PConfig('./config/arrayaccess.json');
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

## APIs

- `set($key, $value)`
- `get($key[, $defult])`
- `delete($key)`
- `exists($key)`
- `getConfig($key)`
- `setConfig($key, $value)`
- `setFile($path)`
- `reload()`
- `clear()`
- `save()`

## Licence

MIT Licence




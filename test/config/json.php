<?php
require '../../vendor/autoload.php';

$config = \test\config\ConfigFactory::getInstance([
    'provider' => [
        'type' => 'file',
        'params' => [
            'file' => 'config/config.json'
        ],
    ],
    'parser' => 'json'
]);

echo '<pre>';
print_r($config->get());
$config->delete('api.qiniu');
$config->delete('api.aliyun.db.2');
$config->set('api.aliyun.db.4', 'Hello oopsguy');
$config->set('hello', 'world');
$config->set('api.aliyun.site', [1,2,3]);
print_r($config->get('api.aliyun.site.2'));
print_r($config->get());


$config->setConfigFile('config/temp_config.json');
$config->save();
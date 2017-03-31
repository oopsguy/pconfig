<?php
require '../../vendor/autoload.php';

$config = \test\config\ConfigFactory::getInstance([
    'provider' => [
        'type' => 'file',
        'params' => [
            'file' => 'config/config.ini'
        ],
    ],
    'parser' => 'ini'
]);

echo '<pre>';
print_r($config->get());
$config->delete('db.rdbms');
$config->delete('hello.world.1.2.a');
$config->set('oss.key2', 'baidu');
print_r($config->get());

$config->setConfigFile('config/temp_config.ini');
$config->save();
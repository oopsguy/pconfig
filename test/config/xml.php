<?php
require '../../vendor/autoload.php';

$config = \test\config\ConfigFactory::getInstance([
    'provider' => [
        'type' => 'file',
        'params' => [
            'file' => 'config/config.xml'
        ]
    ],
    'parser' => 'xml'
]);

echo '<pre>';
print_r($config->get());
print_r($config->get('desc.support'));
$config->delete('title');
$config->set('desc.support.databases', 'no');
print_r($config->get());

$config->setConfigFile('config/temp_config.xml');
$config->save();
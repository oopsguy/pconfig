<?php
require '../../vendor/autoload.php';

$config = \test\config\ConfigFactory::getInstance([
    'provider' => [
        'type' => 'file',
        'params' => [
            'file' => 'config/config.yaml'
        ]
    ],
    'parser' => 'yaml'
]);

echo '<pre>';
print_r($config->get());
$config->set('database.rdbms', null);
$config->delete('highlight.enable');
print_r($config->get());

$config->setConfigFile('config/temp_config.yaml');
$config->save();
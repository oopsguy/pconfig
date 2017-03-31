<?php
require '../../vendor/autoload.php';

$config = \test\config\ConfigFactory::getInstance([
    'provider' => [
        'type' => 'file',
        'params' => [
            'file' => 'config/config.php'
        ]
    ]
]);

echo '<pre>';
print_r($config->get());
//print_r($config->get('api.aliyun.db.oos'));
//$config->set('api.aliyun.db.oos.0', 'HELLOWORLD');
//$config->delete('api.aliyun.db.oos.1');
//var_dump($config->offsetGet('api.aliyun.db.oos'));
//$config->set('api.qiniu', null);
//print_r($config->get());
//

$config->setConfigFile('config/temp_config.php');
$config->save();
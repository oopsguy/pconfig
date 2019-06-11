<?php

use pconfig\ConfigHelper;
use pconfig\Config;
use pconfig\serializer\impl\XMLSerializer;
use pconfig\provider\impl\FileProvider;

require '../vendor/autoload.php';

// simple xml
$xml = ConfigHelper::read('./config/simpleXml.xml');
// print_r($xml->get());
// $xml->set('database', [
//     'oracle', 'mysql', 'db2', 'sqlserver'
// ]);
// $xml->set('config', [
//     'host' => 'localhost111',
//     'username' => 'root',
//     'password'=> 'root',
//     'port' => 3306,
//     'type' => 'mysql'
// ]);
$xml['config.password'] = 'hello world';
unset($xml['config.type']);
$xml->save();

// Custom xml root node name
$parser = new XMLSerializer([
    'root' => 'database'
]);
$provider = new FileProvider('./config/databasesRootNode.xml');
$extConfig = new Config($parser, $provider);
$extConfig->set('type', 'nosql');
$extConfig->set('vendors.vendor', [
    'redis', 'mongo', 'memcache', 'levelDB', 'etc'
]);
$extConfig->save();

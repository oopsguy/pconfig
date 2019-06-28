<?php

use pconfig\PConfig;
use pconfig\serializer\impl\XMLSerializer;
use pconfig\provider\impl\FileProvider;

require '../vendor/autoload.php';

// simple xml
$xml = new PConfig('./config/simpleXml.xml');
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
try {
    $xml->save();
} catch (Exception $e) {
    die($e->getMessage());
}

// Custom xml root node name
$serializer = new XMLSerializer([
    'root' => 'database'
]);
$provider = new FileProvider('./config/databasesRootNode.xml');
try {
    $extConfig = new PConfig([
        'serializer' => $serializer,
        'provider' => $provider
    ]);
} catch (Exception $e) {
    die($e->getMessage());
}
$extConfig->set('type', 'nosql');
$extConfig->set('vendors.vendor', [
    'redis', 'mongo', 'memcache', 'levelDB', 'etc'
]);
try {
    $extConfig->save();
} catch (Exception $e) {
    die($e->getMessage());
}

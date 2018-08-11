<?php
use pconfig\DefaultConfigBuilder;
use pconfig\Config;
use pconfig\parser\impl\XmlParser;
use pconfig\provider\impl\FileProvider;

require '../vendor/autoload.php';

// simple xml
$xml = DefaultConfigBuilder::build('./config/simpleXml.xml');
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
$parser = new XmlParser([
    'root' => 'database'
]);
$provider = new FileProvider(['file' => './config/databasesRootNode.xml']);
$extConfig = new Config($parser, $provider);
$extConfig->set('type', 'nosql');
$extConfig->set('vendors.vendor', [
    'redis', 'mongo', 'memcache', 'levelDB', 'etc'
]);
$extConfig->save();

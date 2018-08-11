<?php
use pconfig\DefaultConfigBuilder;
use pconfig\Config;
use pconfig\parser\impl\YamlParser;
use pconfig\provider\impl\FileProvider;

require '../vendor/autoload.php';

// php array
$config = DefaultConfigBuilder::build("./config/arrayConfig.php");
echo $config->get("app");
$config->delete("version");
$config->set('debug', false);
$config->set("settings.key", "new value");
echo $config['settings.key'] . PHP_EOL;
$config->save();
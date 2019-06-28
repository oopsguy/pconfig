<?php

use pconfig\PConfig;

require '../vendor/autoload.php';

// php array
$config = new PConfig("./config/arrayConfig.php");
echo $config->get("app");
$config->delete("version");
$config->set('debug', false);
$config->set("settings.key", "new value");
echo $config['settings.key'] . PHP_EOL;
try {
    $config->save();
} catch (Exception $e) {
    die($e->getMessage());
}
<?php

use pconfig\ConfigHelper;

require '../vendor/autoload.php';

// php array
$config = ConfigHelper::read("./config/arrayConfig.php");
echo $config->get("app");
$config->delete("version");
$config->set('debug', false);
$config->set("settings.key", "new value");
echo $config['settings.key'] . PHP_EOL;
$config->save();
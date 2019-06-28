<?php

use pconfig\PConfig;

require '../vendor/autoload.php';

// access by index
$json = new PConfig('./config/arrayaccess.json');
$json['status'] = true;
$json['data'] = [
    'page' => 1,
    'pageSize' => 10,
    'pages' => 2,
    'total' => 13,
    'list' => [
        [
            'username' => 'oopsguy',
            'gender' => '男'
        ]
    ]
];
$json['msg'] = 'ok';
$json['delData'] = 'XHSYSYSDkoksoada8dsaidsa9d8adsa';

// unset and isset 
var_dump(isset($json['delData']));
unset($json['delData']);
var_dump(isset($json['delData']));

// save config
try {
    $json->save();
} catch (Exception $e) {
    die($e->getMessage());
}



<?php

require_once 'vendor/autoload.php';

// Displays all array indices and object properties
ini_set('xdebug.var_display_max_children', -1);

// Displays all string data dumped
ini_set('xdebug.var_display_max_data', -1);

// Controls nested level displayed, maximum is 1023
ini_set('xdebug.var_display_max_depth', -1);

$config = new \Configlet\AppConfig();

$config['module.foo'] = 'bar';
$module = $config['module'];

var_dump($module['foo']);

$config['module.foo'] = 'something else';

var_dump($module['foo']);

try {
    $module['foo'] = 'not gonna happen';
} catch (\Configlet\Exception\IllegalConfigOperationException $IllegalOp) {
    var_dump($IllegalOp->getMessage());
}

var_dump($module['foo']);

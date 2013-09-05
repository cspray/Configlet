<?php

defined('CONFIGLET_ROOT') or define('CONFIGLET_ROOT', \dirname(\dirname(__DIR__)));

/** @var \Composer\Autoload\ClassLoader $Loader */
$Loader = require_once \dirname(\dirname(__DIR__)) . '/vendor/autoload.php';
$Loader->set('ConfigletTest', \CONFIGLET_ROOT . '/tests');

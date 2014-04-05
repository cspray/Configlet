<?php

require_once realpath('../../vendor/autoload.php');

/**
 * In this example we take a look at working with the 'app' module configuration
 * of the MasterConfig implementation
 */

use \Configlet\MasterConfig;

$Config = new MasterConfig();
$Config['foo'] = 'bar'; // OR $Config['app.foo'] = 'bar';
$Config['bar'] = 1;
$Config['baz'] = function() {};
$Config['foobar'] = new \stdClass();

/**
 * In the above code we show that we can set values to just about anything that
 * we want. Also note that there are no module separator, '.', in the configuration
 * keys set. With the MasterConfig implementation this means that we implicitly append
 * the 'app' module to these configuration parameters.
 *
 * Let's take a look at how we can retrieve information back
 */

\var_dump($Config['foo']); // string 'bar' (length=3) is the same as ...
\var_dump($Config['app.foo']); // string 'bar' (length=3)

$Config['module.param'] = 'foo';

/**
 * Ok, now we've set our app configuration to have a module; we really don't care
 * about the module configuration, only how it impacts the state of the MasterConfig
 * implementation.
 *
 * Now, let's take a look at the 'app' module configuration. It will only hold the
 * scalar, 'app'-module specific configuration values. All module configurations
 * will be not present.
 */

$AppConfig = $Config[MasterConfig::APP_MODULE];
\var_dump($AppConfig['bar']); // int 1
\var_dump($AppConfig['module.param']); // null
\var_dump($AppConfig['module']); // null

/**
 * Take a look at the next series to see more about how you can work with module
 * configurations.
 */

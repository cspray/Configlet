<?php

/**
 * In this example we take a look at all the various ways of working with the AppConfig
 * object and the 'app' Module configuration.
 */

use \Configlet\AppConfig;

$Config = new AppConfig();
$Config['foo'] = 'bar'; // OR $Config['app.foo'] = 'bar';
$Config['bar'] = 1;
$Config['baz'] = function() {};
$Config['foobar'] = new \stdClass();

/**
 * In the above code we show that we can set values to just about anything that
 * we want. Also note that there are no module separator, '.', in the configuration
 * keys set. With the AppConfig implementation this means that we implicitly append
 * the 'app' module to these configuration parameters.
 *
 * Let's take a look at how we can retrieve information back
 */

\var_dump($Config['foo']); // string 'bar' (length=3) is the same as ...
\var_dump($Config['app.foo']); // string 'bar' (length=3)

$Config['module.param'] = 'foo';

/**
 * Ok, now we've set our app configuration to have a module; we really don't care
 * about the module configuration, only how it impacts the state of the AppConfig
 * implementation.
 *
 * Now, let's take a look at the 'app' module configuration. It will only hold the
 * scalar, 'app'-module specific configuration values. All module configurations
 * will be not present.
 */

$AppConfig = $Config[AppConfig::APP_MODULE];
\var_dump($AppConfig['foo']); // string 'bar' (length=3)
\var_dump($AppConfig['module.param']); // null
\var_dump($AppConfig['module']); // null

/**
 * Take a look at the next series to see more about how you can work with module
 * configurations.
 */

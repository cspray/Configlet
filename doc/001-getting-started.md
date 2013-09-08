# Getting Started

This document is intended to show you how you can use Configlet in new and existing applications to handle your configuration needs. As Configlet matures we plan on updating this document to show off how you can easily integrate the library into popular PHP frameworks.

First, let's take a look at how you might use Configlet to manage your database configuration.

```php
<?php

// this can be in /app/config.php

use \Configlet\AppConfig;

$config = new AppConfig();

$config['debug'] = true;

// you could also read these from a file and set the config we're just showing this way for example
$config['db.name'] = 'dbname';
$config['db.host'] '127.0.0.1';
$config['db.user'] = 'user';
$config['db.password'] = 'password';

$config['configlet.module_return_type'] = AppConfig::IMMUTABLE;

return $config;


// this can be in your /app/bootstrap.php

$config = require_once '/app/config.php';
$dbConfig = $config['db'];

$loadDb = function() use($dbConfig) {
    return new DbConn($dbConfig['name'], $dbConfig['host'], $dbConfig['user'], $dbConfig['password']);
};

// add $loadDb to your service container or otherwise complete execution of the script
```

So, we've used Configlet to store a configuration value indicating our application is in debug mode and we've set some parameters that could be used to connect to a database. We have also ensured that when a module's configuration is retrieved it will be immutable, holding the values that are present in the configuration at the time of the call, and those values will never change unlike the ImmutableProxyConfig.

 What exactly has your application gained though?

 - Encapsulation of configuration writing and reading.

    The writing of the configuration parameters is independent of the rest of the code and can be done early in your bootstrap's lifecycle. More importantly though the reading and consumption of our configuration values can be encapsulated; modules get the configuration that modules need and nothing else.

- No more need for boilerplate `isset()` checks for config keys

    Configlet takes care of all the checking for the presence of configuration values and can gracefully handle configuration values that aren't present; without causing an error to be triggered in your application. Ultimately this makes for less boilerplate code you have to deal with when working with configuration values.

## Next steps

- Figure out how you can configure Configlet to suit your needs by checking out the next tutorial `/doc/002-configuring-configlet.md`
- Fully grok Configlet and the caveats of working with the library by checking out `/doc/003-groking-configlet.md`
- See some examples for ideas on working with Configlet by checking out `/doc/examples`

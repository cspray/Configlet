# Configlet

A PHP library to help manage your application and module configs simply and easily.

## Project Goals

- Provide a simple, powerful API for creating and managing various configurations for your app and modules within that app
- Build all components using [SOLID][solid], readable and thoroughly unit-tested code

## Installation

It is recommended that you install Configlet for use in your application through Composer.

A lot of guides will tell you to run some command to install a composer.phar into the install directory. We're gonna assume that you're smarter than that and have set up a static Composer and you've aliased the appropriate command to, you guessed it, 'composer'.

```shell
composer require cspray/configlet:0.1.*
```

If you have an existing project you can add the following to your composer.json.

```json
{
    "require": {
        "cspray/configlet": "0.1.*"
    }
}
```

If you're anti-Composer or can't install through this method you can always `git clone` this repository or [download a zip of the source][configlet_download]. Please note that if you do not install through Composer you will have to initiate your own autoloading. Configlet should be compatible with any PSR-0 style autoloader.

After you've gotten the library installed it is highly recommended that you check out the Getting Started tutorial in `/doc/001-getting-started.md`.

## Usage

Let's take a look at some of the different ways to use Configlet to provide a host of configuration options; both when writing and reading configurations.

```php
<?php

use \Configlet\AppConfig;

$config = new AppConfig();

$config['debug'] = true;
$config['foo'] = 'bar';
$config['something'] = 'else';
$config['baz'] = 1;
$config['module.param'] = 'configlet';

var_dump($config['foo']); // string 'bar' (length=3)
var_dump($config['module']); // object(Configlet\ImmutableProxyConfig)
var_dump($config['module.param']); // string 'configlet' (length=9)

?>
```

Pretty simple, huh? But, what's going on with the 'module' param dumping an object and what in the world is an `ImmutableProxyConfig`? Well, I'm glad you asked!

All configurations applied to a `Configlet\AppConfig` are done in the form:

`<module>.<parameter>`

The configurations keys set that do not follow this format are automagically included into a module we call 'app' and should deal with app-specific configuration values. The 'module.param' configuration creates a new `Configlet\ModuleConfig` specific to 'module' that stores the appropriate configuration values. When you access a module name, in this case 'module', you get back an object that allows read-only access to that module's configuration. This particular read-only implementation is actually just a proxy to a read-write Config that allows writing of the configuration from the app side and reading from the config consumer side.

This is the basic, "out of the box" behavior for Configlet. Check out `/doc` for ways you can change that behavior and more details about Configlet in general.

[solid]: http://en.wikipedia.org/wiki/SOLID_(object-oriented_design) "S.O.L.I.D."
[configlet_download]: https://github.com/cspray/Configlet/archive/master.zip "Download Configlet"

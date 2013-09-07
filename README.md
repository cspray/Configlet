# Configlet

[![Build Status](https://travis-ci.org/cspray/Configlet.png?branch=master)](https://travis-ci.org/cspray/Configlet) [![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/cspray/Configlet/badges/quality-score.png?s=a2c6952c866b900626fa4e0ca79c7599c587cfbc)](https://scrutinizer-ci.com/g/cspray/Configlet/) [![Code Coverage](https://scrutinizer-ci.com/g/cspray/Configlet/badges/coverage.png?s=47ffbd796840229593c9f31f090683f7c45e65d2)](https://scrutinizer-ci.com/g/cspray/Configlet/)

> This library is still under construction and has not been released yet

A PHP library to help manage your application and module configs simply and easily. Check out the Roadmap in `/doc/000-roadmap.md` for new and upcoming features.

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

If you're anti-Composer or can't install through this method you can always `git clone https://github.com/cspray/Configlet.git` this repository or [download a zip of the source][configlet_download]. Please note that if you do not install through Composer you will have to initiate your own autoloading. Configlet should be compatible with any PSR-0 style autoloader.

After you've gotten the library installed it is highly recommended that you check out the Getting Started tutorial in `/doc/001-getting-started.md`.

## Usage

Let's take a look at some of the different ways to use Configlet to provide a host of configuration options; both when writing and reading configurations.

```php
<?php

use \Configlet\MasterConfig;

$config = new MasterConfig();

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

All configurations applied to a `Configlet\MasterConfig` are done in the form:

`<module>.<parameter>`

The configurations keys set that do not follow this format are automagically included into a module we call 'app' (e.g. 'foo' is equivalent to 'app.foo'). It is recommended that app wide or non-module specific applications should go here. The 'module.param' configuration creates a new `Configlet\MutableConfig` specific to 'module' that stores the appropriate configuration values. When you access a module name, in this case 'module', you get back an object that allows read-only access to that module's configuration. This particular read-only implementation is actually just a proxy to the MutableConfig that allows writing of the configuration from the app side and reading from the config consumer side.

This is the basic, "out of the box" behavior for Configlet. Check out `/doc` for ways you can change that behavior and more details about Configlet in general.

[solid]: http://en.wikipedia.org/wiki/SOLID_(object-oriented_design) "S.O.L.I.D."
[configlet_download]: https://github.com/cspray/Configlet/archive/master.zip "Download Configlet"

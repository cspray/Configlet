<?php

require_once realpath('../../vendor/autoload.php');

/**
 * In this example we're gonna take a look at expanding on the code example we
 * demonstrated in the Getting Started doc. We'll demonstrate how you might use
 * Configlet to provide a configuration for multiple database connections.
 */

use \Configlet\ImmutableConfig;
use \Configlet\MasterConfig;

$Config = new MasterConfig();
$Config['db.yourapp.host'] = 'localhost';
$Config['db.yourapp.user'] = 'yourapp_user';
$Config['db.yourapp.pass'] = 'yourapp_pass';
$Config['db.yourapp.name'] = 'yourapp_name';

$Config['db.configlet.host'] = '127.0.0.1';
$Config['db.configlet.user'] = 'configlet_user';
$Config['db.configlet.pass'] = 'configlet_pass';
$Config['db.configlet.name'] = 'config_db';

$DbConfig = $Config['db'];

$getConnection = function($dbName = 'yourapp') use($DbConfig) {
    $dsn = \sprintf('msyql:dbname=%s;host=%s', $DbConfig[$dbName . '.name'], $DbConfig[$dbName . '.host']);
    $user = $DbConfig[$dbName . '.user'];
    $pass = $DbConfig[$dbName . '.pass'];
    return new \DemoConn($dsn, $user, $pass);
};

\var_dump($getConnection('yourapp'));
\var_dump($getConnection('configlet'));

/**
 * In the above example we've shown how you can setup a 'db' module config to allow
 * multiple database connections and a very simple connection "factory" that uses
 * the configuration.
 *
 * The example above has a lot of ugly string concatenation and doesn't look very
 * clean, let's see how we can use Configlet to fix that. Here's a new example
 * of the above code while providing a configuration for each specific database
 * we might connect to.
 */

$Config = new MasterConfig();
$Config['db.yourapp'] = new ImmutableConfig('db.yourapp', [
    'host' => 'localhost',
    'user' => 'yourapp_user',
    'pass' => 'yourapp_pass',
    'name' => 'yourapp_name'
]);
$Config['db.configlet'] = new ImmutableConfig('db.configlet', [
    'host' => '127.0.0.1',
    'user' => 'configlet_user',
    'pass' => 'configlet_pass',
    'name' => 'configlet_name'
]);

$DbConfig = $Config['db'];
$getConnection = function($dbName = 'yourapp') use($DbConfig) {
    $dsn = \sprintf('mysql:dbname=%s;host=%s', $DbConfig[$dbName]['name'], $DbConfig[$dbName]['host']);
    $user = $DbConfig[$dbName]['user'];
    $pass = $DbConfig[$dbName]['pass'];
    return new \DemoConn($dsn, $user, $pass);
};

\var_dump($getConnection());
\var_dump($getConnection('configlet'));

/**
 * A demo database connection that allows this example to be self contained and
 * demonstrated without needing to have actual databases up.
 */
class DemoConn {

    public $dsn;
    public $user;
    public $pass;

    public function __construct($dsn, $user, $pass) {
        $this->dsn = $dsn;
        $this->user = $user;
        $this->pass = $pass;
    }

}

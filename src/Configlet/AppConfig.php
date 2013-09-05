<?php

/**
 * Implementation of Configlet\Config that acts as an overall or master configuration
 * for an app.
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1
 * @since   0.1
 */

namespace Configlet;

use \Configlet\Config;
use \Configlet\Exception;
use \IteratorAggregate;
use \ArrayIterator;

/**
 * @property \Configlet\Config[] $modules
 * @property \Configlet\Config[] $proxyCache
 */
class AppConfig implements IteratorAggregate, Config {

    const APP_MODULE = 'app';

    const IMMUTABLE_PROXY = 'immutable_proxy';
    const IMMUTABLE = 'immutable';
    const MUTABLE = 'mutable';

    /**
     * A collection of MutableConfig objects that allow writing module specific
     * parameter values.
     *
     * [module => Config]
     *
     * @property \Configlet\Config[]
     */
    private $modules = [];

    /**
     * A cache of ImmutableProxyConfig objects so we don't create unneccessary
     * objects for successive calls to the same module.
     *
     * [module => Config]
     *
     * @property \Configlet\Config[]
     */
    private $proxyCache = [];

    /**
     * We are ensuring the app module is set to a configuration so that if a module
     * is not appropriately set we return an object and not just a null value
     */
    public function __construct() {
        $this->modules[self::APP_MODULE] = new MutableConfig(self::APP_MODULE);

    }

    /**
     * Returns 'master' as this is the configuration keeping up with the whole
     * thing.
     *
     * @return string
     */
    public function getModuleName() {
        return 'master';
    }

    /**
     *
     *
     * @param string $offset
     * @return boolean
     */
    public function offsetExists($offset) {
        return isset($this->modules[self::APP_MODULE][$offset]);
    }

    /**
     *
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset) {
        if (isset($this->modules[$offset])) {
            return $this->getModuleConfig($offset);
        }

        list($module, $parameter) = $this->getModuleAndParameter($offset);

        if (!isset($this->modules[$module])) {
            return null;
        }

        return $this->modules[$module][$parameter];
    }

    /**
     * @param string $offset
     * @param mixed $value
     * @return void
     *
     * @throws \Configlet\Exception\IllegalConfigOperationException
     */
    public function offsetSet($offset, $value) {
        list($module, $parameter) = $this->getModuleAndParameter($offset);

        if (!isset($this->modules[$module])) {
            $this->modules[$module] = new MutableConfig($module);
        }

        $this->modules[$module][$parameter] = $value;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset) {
        unset($this->modules[self::APP_MODULE][$offset]);
    }

    private function getModuleConfig($module) {
        if ($this['configlet.module_return_type'] === self::MUTABLE) {
            return $this->modules[$module];
        }

        if (!isset($this->proxyCache[$module])) {
            $this->proxyCache[$module] = new ImmutableProxyConfig($this->modules[$module]);
        }

        return $this->proxyCache[$module];
    }

    private function getModuleAndParameter($offset) {
        $return = [self::APP_MODULE, $offset];

        // we are not doing a strict boolean check here for a reason
        // if you really did have the '.' as the first character that means
        // when we explode our $module is '.' which makes no sense
        if (\strpos($offset, '.')) {
            $return = \explode('.', $offset);
        }

        return $return;
    }

    public function getIterator() {
        return new ArrayIterator($this->modules);
    }

}

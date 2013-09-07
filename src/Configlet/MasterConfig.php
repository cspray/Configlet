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
use \Configlet\ConfigTrait\ConfigKeyValidator;
use \Configlet\Exception\IllegalConfigOperationException;
use \IteratorAggregate;
use \ArrayIterator;

/**
 * @property \Configlet\Config[] $modules
 * @property \Configlet\Config[] $proxyCache
 */
class MasterConfig implements IteratorAggregate, Config {

    use ConfigKeyValidator;

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
        $this->validateKey($offset);
        if (isset($this->modules[$offset])) {
            return true;
        }

        list($module, $parameter) = $this->getModuleAndParameter($offset);
        return isset($this->modules[$module][$parameter]);
    }

    /**
     *
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset) {
        $this->validateKey($offset);
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
        $this->validateKey($offset);
        if (isset($this->modules[$offset])) {
            $message = 'You may not set a parameter that shares a name with a module configuration';
            throw new IllegalConfigOperationException($message);
        }

        list($module, $parameter) = $this->getModuleAndParameter($offset);
        if (!isset($this->modules[$module])) {
            $this->modules[$module] = new MutableConfig($module);
        }

        $this->modules[$module][$parameter] = $value;
    }

    /**
     * Will destroy a module configuration parameter or an entire module configuration.
     *
     * @param string $offset
     * @return void
     */
    public function offsetUnset($offset) {
        $this->validateKey($offset);
        if (isset($this->modules[$offset])) {
            unset($this->modules[$offset]);
            return;
        }

        unset($this->modules[self::APP_MODULE][$offset]);
    }

    private function getModuleConfig($module) {
        $moduleReturnType = $this['configlet.module_return_type'];
        if ($moduleReturnType === self::MUTABLE) {
            return $this->modules[$module];
        }

        if ($moduleReturnType === self::IMMUTABLE) {
            $data = [];
            foreach($this->modules[$module] as $key => $val) {
                $data[$key] = $val;
            }

            return new ImmutableConfig($module, $data);
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

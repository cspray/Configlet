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
     * Returns a boolean value for whether key matching $parameter can be found.
     *
     * This implementation will return true if $offset is a name of a module with
     * a configuration.
     *
     * @param string $parameter
     * @return boolean
     */
    public function offsetExists($parameter) {
        $this->validateKey($parameter);
        if (isset($this->modules[$parameter])) {
            return true;
        }

        list($module, $parameter) = $this->getModuleAndParameter($parameter);
        return isset($this->modules[$module][$parameter]);
    }

    /**
     * Returns a value associated to $parameter or null if that value could not
     * be found.
     *
     * If $parameter matches a module with a configuration a Configlet\Config
     * implementation will be returned. You can adjust the type of implementation
     * returned by setting MasterConfig[configlet.module_return_type].
     *
     * @param string $parameter
     * @return mixed
     *
     * @see /doc/002-configuring-configlet.md
     */
    public function offsetGet($parameter) {
        $this->validateKey($parameter);
        if (isset($this->modules[$parameter])) {
            return $this->getModuleConfig($parameter);
        }

        list($module, $parameter) = $this->getModuleAndParameter($parameter);

        if (!isset($this->modules[$module])) {
            return null;
        }

        return $this->modules[$module][$parameter];
    }

    /**
     * Will set the configuration $parameter to associated value.
     *
     * If the $parameter is the name of a module with a configuration set an
     * exception will be thrown as this is considered an illegal operation for
     * this configuration.
     *
     * @param string $parameter
     * @param mixed $value
     * @return void
     *
     * @throws \Configlet\Exception\IllegalConfigOperationException
     */
    public function offsetSet($parameter, $value) {
        $this->validateKey($parameter);
        if (isset($this->modules[$parameter])) {
            $message = 'You may not set a parameter that shares a name with a module configuration';
            throw new IllegalConfigOperationException($message);
        }

        list($module, $parameter) = $this->getModuleAndParameter($parameter);
        if (!isset($this->modules[$module])) {
            $this->modules[$module] = new MutableConfig($module);
        }

        $this->modules[$module][$parameter] = $value;
    }

    /**
     * Will destroy a module configuration parameter or an entire module configuration.
     *
     * @param string $parameter
     * @return void
     */
    public function offsetUnset($parameter) {
        $this->validateKey($parameter);
        if (isset($this->modules[$parameter])) {
            unset($this->modules[$parameter]);
            return;
        }

        list($module, $parameter) = $this->getModuleAndParameter($parameter);
        unset($this->modules[$module][$parameter]);
    }

    /**
     * @param string $module
     * @return \Configlet\Config
     */
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

    /**
     * @param string $offset
     * @return array
     */
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

    /**
     * Allows to iterate over the module configurations set.
     *
     * @return \ArrayIterator
     */
    public function getIterator() {
        return new ArrayIterator($this->modules);
    }

}

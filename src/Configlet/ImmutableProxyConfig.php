<?php

/**
 * Implementation of Configlet\Config that does not allow the mutation of data
 * and reads data from a different Config implementation.
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1
 * @since   0.1
 */

namespace Configlet;

use IteratorIterator;

/**
 * The primary use for this configuration is that you can provide a read-only end
 * to consumers of the configuration while still allowing writing on the end that
 * sets the configuration.
 *
 * By utilizing this object you have an amount of assurance that the configuration
 * given to a consumer cannot be changed by that consumer and that, if the configuration
 * is properly utilized, the values you expect will be there before and after the
 * operation.
 */
class ImmutableProxyConfig extends ImmutableConfig {

    /**
     * @property \Configlet\Config
     */
    private $proxy;

    /**
     * @param \Configlet\Config $config
     */
    public function __construct(Config $config) {
        $this->proxy = $config;
    }

    /**
     * Returns the module for the Config object being proxied.
     *
     * @return string
     */
    public function getModuleName() {
        return $this->proxy->getModuleName();
    }

    /**
     * Returns whether the parameter exists for the Config object being proxied
     *
     * @param string $offset
     * @return boolean
     */
    public function offsetExists($offset) {
        return isset($this->proxy[$offset]);
    }

    /**
     * Returns the value for the Config object being proxied
     *
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset) {
        return $this->proxy[$offset];
    }

    public function getIterator() {
        return new IteratorIterator($this->proxy);
    }

}

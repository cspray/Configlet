<?php

/**
 * An abstract Configlet\Config that provides basic functionality for getting the
 * module name for the config, getting and checking values as well as iterating
 * over the key/value store.
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1
 * @since   0.1
 */

namespace Configlet;

use \IteratorAggregate;
use \ArrayIterator;

abstract class BaseConfig implements IteratorAggregate, Config {

    /**
     * @property array
     */
    protected $store = [];

    /**
     * @property string
     */
    private $name = '';

    /**
     * @param string $moduleName
     */
    public function __construct($moduleName) {
        $this->name = (string) $moduleName;
    }

    /**
     * The name of the application module this configuration belongs to.
     *
     * @return string
     */
    public function getModuleName() {
        return $this->name;
    }

    /**
     * @param string $offset
     * @return boolean
     */
    public function offsetExists($offset) {
        return \array_key_exists($offset, $this->store);
    }

    /**
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset) {
        if (!isset($this[$offset])) {
            return null;
        }
        return $this->store[$offset];
    }

    /**
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator() {
        return new ArrayIterator($this->store);
    }

}

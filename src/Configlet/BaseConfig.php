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

use Configlet\ConfigTrait\ConfigKeyValidator;
use \Configlet\Exception\IllegalConfigOperationException;
use \IteratorAggregate;
use \ArrayIterator;


abstract class BaseConfig implements IteratorAggregate, Config {

    use ConfigKeyValidator;

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
     *
     * @throws \Configlet\Exception\IllegalConfigOperationException
     */
    public function __construct($moduleName) {
        if (!\is_string($moduleName)) {
            $message = 'The module name must be a string value and a type of \'%s\' was given';
            throw new IllegalConfigOperationException(\sprintf($message, \gettype($moduleName)));
        }

        if (empty($moduleName)) {
            throw new IllegalConfigOperationException('You must set a valid, non-empty module name for Configlet configurations');
        }

        $this->name = $moduleName;
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
        $this->validateKey($offset);
        return \array_key_exists($offset, $this->store);
    }

    /**
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset) {
        $this->validateKey($offset);
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

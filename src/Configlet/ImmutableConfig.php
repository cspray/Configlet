<?php

/**
 * An implementation of Configlet\Config and child of Configlet\BaseConfig that
 * only allows reading of data injected at construction time.
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1
 * @since   0.1
 */

namespace Configlet;

use \Configlet\Exception\IllegalConfigOperationException;

class ImmutableConfig extends BaseConfig implements Config {

    /**
     * The $store should be passed in as an associative [key => val] array.
     *
     * @param string $moduleName
     * @param array $store
     */
    public function __construct($moduleName, array $store) {
        parent::__construct($moduleName);
    }

    /**
     * This is an invalid operation on an immutable object.
     *
     * @param string $offset
     * @param mixed $value
     *
     * @throws \Configlet\Exception\IllegalConfigOperationException
     */
    public function offsetSet($offset, $value) {
        $message = 'You may not change the value of a %s';
        throw new IllegalConfigOperationException(\sprintf($message, __CLASS__));
    }

    /**
     * This is an invalid operation on an immutable object.
     *
     * @param string $offset
     *
     * @throws \Configlet\Exception\IllegalConfigOperationException
     */
    public function offsetUnset($offset) {
        $message = 'You may not unset the value of a %s';
        throw new IllegalConfigOperationException(\sprintf($message, __CLASS__));
    }

}

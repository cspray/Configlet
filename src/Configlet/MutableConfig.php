<?php

/**
 * Implementation of Configlet\Config that allows reading, writing and deleting
 * of key/value pairs in the configuration.
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1
 * @since   0.1
 */

namespace Configlet;

use \Configlet\Exception\IllegalConfigOperationException;

class MutableConfig extends BaseConfig {

    /**
     * @param string $offset
     * @param mixed $value
     * @return void
     *
     * @throws \Configlet\Exception\IllegalConfigOperationException
     */
    public function offsetSet($offset, $value) {
        $this->validateKey($offset);
        $this->store[$offset] = $value;
    }

    /**
     * @param string $offset
     * @return void
     */
    public function offsetUnset($offset) {
        $this->validateKey($offset);
        unset($this->store[$offset]);
    }

}

<?php

/**
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 */

namespace Configlet;

use \Configlet\Exception\IllegalConfigOperationException;

class ImmutableProxyConfig implements Config {

    /**
     * @property \Configlet\Config
     */
    private $Proxy;

    public function __construct(Config $Config) {
        $this->Proxy = $Config;
    }

    /**
     *
     *
     * @return string
     */
    public function getModuleName() {
        return $this->Proxy->getModuleName();
    }

    /**
     *
     *
     * @param string $offset
     * @return boolean
     */
    public function offsetExists($offset) {
        return isset($this->Proxy[$offset]);
    }

    /**
     *
     *
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset) {
        return $this->Proxy[$offset];
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

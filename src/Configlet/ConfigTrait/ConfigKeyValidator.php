<?php

/**
 * A trait that will validate a given value should be considered a valid configuration
 * key for Configlet; please see class level docs for reasoning's behind implementing
 * this as a trait.
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1
 * @since   0.1
 */

namespace Configlet\ConfigTrait;


use Configlet\Exception\IllegalConfigOperationException;

/**
 * Traits are an interesting part of the programming language but one that can be
 * open to abuse if improperly utilized; there are 2 primary reasons for the existence
 * of this trait.
 *
 * # There are a lot of configs all dealing with similar constraints on configuration keys
 *
 * As of the time of this writing there are currently 5 implementations that work
 * with configuration keys. That means 5 manners of duplication all to validate
 * the same thing. We could make this an abstract class but some implementations
 * that still need to validate configuration keys may not need to extend from this
 * class (see Configlet\AppConfig)
 *
 * # It doesn't expose public facing functionality
 *
 * We aren't exposing anything that the public facing consumers can do to the state
 * of the object. Ultimately this trait is an implementation detail truly hidden
 * from the public facing API so you kinda shouldn't even care that we're using
 * this ;)
 */
trait ConfigKeyValidator {

    /**
     * Ensures that the $key passed is a non-empty, string value; if no exception
     * is thrown the $key is considered valid
     *
     * If a $key is invalid an exception will be thrown with appropriate messages
     * detailing how the $key is invalid.
     *
     * @param string $key
     * @return void
     *
     * @throws \Configlet\Exception\IllegalConfigOperationException
     */
    protected function validateKey($key) {
        $message = '';
        if (!\is_string($key)) {
            $message = 'A Configlet\\Config key must be a string and a \'' . \gettype($key) . '\' was given';
        } else {
            if (empty($key)) {
                $message = 'A Configlet\\Config key value may not be set to an empty string';
            }
        }

        if ($message) {
            throw new IllegalConfigOperationException($message);
        }
    }

}

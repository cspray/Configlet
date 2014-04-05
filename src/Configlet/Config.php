<?php

/**
 * An interface that represents a set of configuration values for an app, library
 * or module.
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1
 * @since   0.1
 */

namespace Configlet;

use \ArrayAccess;
use \Traversable;

interface Config extends ArrayAccess, Traversable {

    /**
     * The name of the module this configuration belongs to.
     *
     * @return string
     */
    public function getModuleName();

}

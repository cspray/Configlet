<?php

/**
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
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

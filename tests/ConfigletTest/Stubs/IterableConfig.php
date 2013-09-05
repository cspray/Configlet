<?php

/**
 * Allows the mocking of the Configlet\Config interface; because we extend Traversable
 * we must implement Iterator or IteratorAggregate BEFORE we list Config.
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1
 * @since   0.1
 */

namespace ConfigletTest\Stubs;

use \Configlet\Config;
use \IteratorAggregate;

interface IterableConfig extends IteratorAggregate, Config {}

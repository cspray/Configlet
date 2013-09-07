<?php

/**
 * This stub is here to provide public access to the Trait under test's protected
 * method.
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1
 * @since   0.1
 */

namespace ConfigletTest\Stubs;

use Configlet\ConfigTrait\ConfigKeyValidator;

class ConfigKeyValidatorStub {

    use ConfigKeyValidator;

    public function doValidateKey($key) {
        return $this->validateKey($key);
    }

}

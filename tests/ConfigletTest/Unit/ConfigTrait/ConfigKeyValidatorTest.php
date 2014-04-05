<?php

/**
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1
 * @since   0.1
 */

namespace ConfigletTest\Unit\Config\ConfigTrait;

use ConfigletTest\Stubs\ConfigKeyValidatorStub;

/**
 * @property \ConfigletTest\Stubs\ConfigKeyValidatorStub $Trait
 */
class ConfigKeyValidatorTest extends \PHPUnit_Framework_TestCase {

    private $Trait;
    private $configletException;

    public function setUp() {
        $this->Trait = new ConfigKeyValidatorStub();
        $this->configletException = '\\Configlet\\Exception\\IllegalConfigOperationException';
    }

    public function testValidatingNonStringKeyThrowsException() {
        $message = 'A Configlet\\Config key must be a string and a \'integer\' was given';
        $this->setExpectedException($this->configletException, $message);
        $this->Trait->doValidateKey(1);
    }

    public function testValidatingEmptyStringKeyThrowsException() {
        $message = 'A Configlet\\Config key value may not be set to an empty string';
        $this->setExpectedException($this->configletException, $message);
        $this->Trait->doValidateKey('');
    }

    public function testSettingEmptyNonStringValueResultsInAppropriateMessage() {
        $message = 'A Configlet\\Config key must be a string and a \'array\' was given';
        $this->setExpectedException($this->configletException, $message);
        $this->Trait->doValidateKey([]);
    }

    public function testValidatingNonEmptyStringKeyDoesNotThrowExceptionAndReturnsNull() {
        $this->assertNull($this->Trait->doValidateKey('something'));
    }

}

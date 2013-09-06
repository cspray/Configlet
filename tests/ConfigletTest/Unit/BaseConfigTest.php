<?php
/**
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 */

namespace ConfigletTest\Unit\Config;

use \ConfigletTest\Stubs\BaseConfigStub;

class BaseConfigTest extends \PHPUnit_Framework_TestCase {

    public function testGettingModuleName() {
        $Config = $this->getMockForAbstractClass('\\Configlet\\BaseConfig', ['foo']);
        $this->assertSame('foo', $Config->getModuleName());
    }

    public function testSettingEmptyModuleNameThrowsException() {
        $message = 'You must set a valid, non-empty module name for Configlet configurations';
        $this->setExpectedException('\\Configlet\\Exception\\IllegalConfigOperationException', $message);
        $Config = $this->getMockForAbstractClass('\\Configlet\\BaseConfig', ['']);
    }

    public function testSettingNonStringModuleNameThrowsException() {
        $message = 'The module name must be a string value and a type of \'boolean\' was given';
        $this->setExpectedException('\\Configlet\\Exception\\IllegalConfigOperationException', $message);
        $Config = $this->getMockForAbstractClass('\\Configlet\\BaseConfig', [true]);
    }

    public function testGettingIteratorAfterSettingValues() {
        $Config = $this->getMockForAbstractClass('\\Configlet\\BaseConfig', ['foo']);
        $offsetSet = function($key, $value) {
            $this->store[$key] = $value;
        };

        $offsetSet = $offsetSet->bindTo($Config, $Config);
        $Config->expects($this->exactly(3))
               ->method('offsetSet')
               ->will($this->returnCallback($offsetSet));

        $Config['foo'] = 'bar';
        $Config['baz'] = 'configlet';
        $Config['something'] = ['a', 'b', 'c'];

        $actual = [];
        foreach($Config as $key => $val) {
            $actual[$key] = $val;
        }

        $expected = [
            'foo' => 'bar',
            'baz' => 'configlet',
            'something' => ['a', 'b', 'c']
        ];
        $this->assertSame($expected, $actual);
    }

}

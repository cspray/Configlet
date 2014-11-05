<?php
/**
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 */

namespace ConfigletTest\Unit\Config;

use Configlet\ImmutableProxyConfig;
use Configlet\MutableConfig;
use ConfigletTest\Stubs\IterableConfig;

class ImmutableProxyConfigTest extends \PHPUnit_Framework_TestCase {

    public function testSettingImmutableProxyThrowsException() {
        $config = new ImmutableProxyConfig($this->getMock('\\ConfigletTest\\Stubs\\IterableConfig'));
        $message = 'You may not change the value of a Configlet\\ImmutableConfig';
        $this->setExpectedException('\\Configlet\\Exception\\IllegalConfigOperationException', $message);
        $config['foo'] = 'nah uh, not gonna happen';
    }

    public function testUnsettingImmutableProxyThrowException() {
        $config = new ImmutableProxyConfig($this->getMock('\\ConfigletTest\\Stubs\\IterableConfig'));
        $message = 'You may not unset the value of a Configlet\\ImmutableConfig';
        $this->setExpectedException('\\Configlet\\Exception\\IllegalConfigOperationException', $message);
        unset($config['foo']);
    }

    public function testCallingOffsetGetCallsProxy() {
        $config = new ImmutableProxyConfig($mock = $this->getMock('\\ConfigletTest\\Stubs\\IterableConfig'));
        $mock->expects($this->once())
             ->method('offsetGet')
             ->with('foo')
             ->will($this->returnValue('bar'));

        $this->assertSame('bar', $config['foo']);
    }

    public function testCallingOffsetExistsCallsProxy() {
        $config = new ImmutableProxyConfig($mock = $this->getMock('\\ConfigletTest\\Stubs\\IterableConfig'));
        $mock->expects($this->once())
             ->method('offsetExists')
             ->with('foo')
             ->will($this->returnValue(true));

        $this->assertTrue(isset($config['foo']));
    }

    public function testCallingGetModuleNameCallsProxy() {
        $config = new ImmutableProxyConfig($mock = $this->getMock('\\ConfigletTest\\Stubs\\IterableConfig'));
        $mock->expects($this->once())
             ->method('getModuleName')
             ->will($this->returnValue('foo'));

        $this->assertSame('foo', $config->getModuleName());
    }

    public function testIteratingOverImmutableProxy() {
        $proxy = new MutableConfig('something');
        $config = new ImmutableProxyConfig($proxy);
        $proxy['a'] = 'foo';
        $proxy['b'] = 'bar';
        $proxy['c'] = 'baz';
        $actual = [];
        foreach ($config as $key => $val) {
            $actual[$key] = $val;
        }

        $this->assertSame(['a' => 'foo', 'b' => 'bar', 'c' => 'baz'], $actual);
    }

}

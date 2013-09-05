<?php
/**
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 */

namespace ConfigletTest\Unit\Config;

use \Configlet\ImmutableProxyConfig;

class ImmutableProxyConfigTest extends \PHPUnit_Framework_TestCase {

    public function testSettingImmutableProxyThrowsException() {
        $Config = new ImmutableProxyConfig($this->getMock('\\ConfigletTest\\Stubs\\IterableConfig'));
        $message = 'You may not change the value of a Configlet\\ImmutableConfig';
        $this->setExpectedException('\\Configlet\\Exception\\IllegalConfigOperationException', $message);
        $Config['foo'] = 'nah uh, not gonna happen';
    }

    public function testUnsettingImmutableProxyThrowException() {
        $Config = new ImmutableProxyConfig($this->getMock('\\ConfigletTest\\Stubs\\IterableConfig'));
        $message = 'You may not unset the value of a Configlet\\ImmutableConfig';
        $this->setExpectedException('\\Configlet\\Exception\\IllegalConfigOperationException', $message);
        unset($Config['foo']);
    }

    public function testCallingOffsetGetCallsProxy() {
        $Config = new ImmutableProxyConfig($Mock = $this->getMock('\\ConfigletTest\\Stubs\\IterableConfig'));
        $Mock->expects($this->once())
             ->method('offsetGet')
             ->with('foo')
             ->will($this->returnValue('bar'));

        $this->assertSame('bar', $Config['foo']);
    }

    public function testCallingOffsetExistsCallsProxy() {
        $Config = new ImmutableProxyConfig($Mock = $this->getMock('\\ConfigletTest\\Stubs\\IterableConfig'));
        $Mock->expects($this->once())
             ->method('offsetExists')
             ->with('foo')
             ->will($this->returnValue(true));

        $this->assertTrue(isset($Config['foo']));
    }

    public function testCallingGetModuleNameCallsProxy() {
        $Config = new ImmutableProxyConfig($Mock = $this->getMock('\\ConfigletTest\\Stubs\\IterableConfig'));
        $Mock->expects($this->once())
             ->method('getModuleName')
             ->will($this->returnValue('foo'));

        $this->assertSame('foo', $Config->getModuleName());
    }

}

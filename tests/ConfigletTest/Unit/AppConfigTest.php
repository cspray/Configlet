<?php

/**
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 */

namespace ConfigletTest\Unit\Config;

use \Configlet\AppConfig;

class AppConfigTest extends \PHPUnit_Framework_TestCase {

    public function testSettingParameterWithNoModuleSeparatorAddsToApplicationConfig() {
        $Config = new AppConfig();
        $Config['foo'] = 'bar';

        /** @var \Configlet\ModuleConfig $AppStore */
        $AppStore = $Config[$Config::APP_MODULE];
        $this->assertInstanceOf('\\Configlet\\ImmutableProxyConfig', $AppStore);
        $this->assertSame('bar', $AppStore['foo']);
    }

    public function testSettingParameterWithNoModuleSeparatorReturnsSameValueWhenGetting() {
        $Config = new AppConfig();
        $Config['foo'] = 'bar';
        $this->assertSame('bar', $Config['foo']);
    }

    public function testSettingNewModuleParameterCreatesAppropriateModuleConfig() {
        $Config = new AppConfig();
        $Config['foo.bar'] = 'foobar';
        $FooStore = $Config['foo'];
        $this->assertInstanceOf('\\Configlet\\ImmutableProxyConfig', $FooStore);
    }

    public function testSettingNewModuleParameterAlsoSetsAppropriateValueInModuleConfig() {
        $Config = new AppConfig();
        $Config['foo.bar'] = 'foobar';
        $FooStore = $Config['foo'];
        $this->assertSame('foobar', $FooStore['bar']);
    }

    public function testGettingModuleParameterReturnsAppropriateValue() {
        $Config = new AppConfig();
        $Config['foo.bar'] = 'foobar';
        $this->assertSame('foobar', $Config['foo.bar']);
    }

    public function testCheckingAppParameterDoesNotExistReturnsFalse() {
        $Config = new AppConfig();
        $this->assertFalse(isset($Config['not-there']));
    }

    public function testGettingModuleParameterNotSetReturnsNull() {
        $Config = new AppConfig();
        $this->assertNull($Config['foo.bar']);
    }

    public function testChangingReturnTypeWhenRetrievingModuleConfig() {
        $Config = new AppConfig();
        $Config['configlet.module_return_type'] = AppConfig::MUTABLE;
        $this->assertInstanceOf('\\Configlet\\MutableConfig', $Config['configlet']);
    }

    public function testSwappingReturnTypeBackAndForthRetrievesAppropriateObjects() {
        $Config = new AppConfig();
        $Config['configlet.module_return_type'] = AppConfig::MUTABLE;
        $this->assertInstanceOf('\\Configlet\\MutableConfig', $Config['configlet']);
        $Config['configlet.module_return_type'] = AppConfig::IMMUTABLE_PROXY;
        $this->assertInstanceOf('\\Configlet\\ImmutableProxyConfig', $Config['configlet']);
    }

    public function testGettingBackToBackImmutableProxyReturnsCachedObject() {
        $Config = new AppConfig();
        $Config['configlet.something'] = 'just getting module loaded';
        $this->assertInstanceOf('\\Configlet\\ImmutableProxyConfig', $first = $Config['configlet']);
        $second = $Config['configlet'];
        $this->assertSame($first, $second);
    }

}

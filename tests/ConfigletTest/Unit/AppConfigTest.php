<?php

/**
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 */

namespace ConfigletTest\Unit\Config;

use \Configlet\AppConfig;

class AppConfigTest extends \PHPUnit_Framework_TestCase {

    public function testGettingModuleName() {
        $Config = new AppConfig();
        $this->assertSame('master', $Config->getModuleName());
    }

    public function testSettingParameterWithNoModuleSeparatorAddsToApplicationConfig() {
        $Config = new AppConfig();
        $Config['foo'] = 'bar';

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

    public function testUnsettingSetValueDestroysAppropriateKey() {
        $Config = new AppConfig();
        $Config['something'] = 'yea';
        $this->assertSame('yea', $Config['something']);
        unset($Config['something']);
        $this->assertNull($Config['something']);
    }

    public function testIteratingOverConfigs() {
        $Config = new AppConfig();
        $Config['foo'] = 'getting app module';
        $Config['configlet.something'] = 'a configlet module config';
        $Config['foo.bar'] = 'baz';

        $actual = [];
        /** @var \Configlet\MutableConfig $MutableConfig */
        foreach($Config as $module => $MutableConfig) {
            $actual[$module] = $MutableConfig->getModuleName();
        }

        $expected = ['app' => 'app', 'configlet' => 'configlet', 'foo' => 'foo'];
        $this->assertSame($expected, $actual);
    }

    public function testSettingModuleConfigurationReturnsTrueCheckingForThatValueExistence() {
        $Config = new AppConfig();
        $Config['configlet.foo'] = 'something';
        $this->assertSame('something', $Config['configlet.foo']);
        $this->assertTrue(isset($Config['configlet.foo']));
    }

    public function testCheckExistenceOfModuleParameterBeforeAndAfterSettingModuleConfiguration() {
        $Config = new AppConfig();
        $this->assertFalse(isset($Config['module']));
        $Config['module.param'] = 'foo';
        $this->assertTrue(isset($Config['module']));
    }

    public function testSettingModuleReturnTypeToImmutableGivesUsAnImmutableObject() {
        $Config = new AppConfig();
        $Config['configlet.foo'] = 'something';
        $Config['configlet.module_return_type'] = AppConfig::IMMUTABLE;

        $CfgltStore = $Config['configlet'];
        $this->assertInstanceOf('\\Configlet\\ImmutableConfig', $CfgltStore);
        $this->assertNotInstanceOf('\\Configlet\\ImmutableProxyConfig', $CfgltStore);
        $this->assertSame('configlet', $CfgltStore->getModuleName());
        $this->assertSame('something', $CfgltStore['foo']);
    }

    public function testSettingModuleWithEmptyStringThrowsException() {
        $Config = new AppConfig();
        $message = 'A configuration key must be a string and a value with type \'array\' was provided';
        $this->setExpectedException('\\Configlet\\Exception\\IllegalConfigOperationException', $message);
        $Config[[]] = 'something';

    }



}

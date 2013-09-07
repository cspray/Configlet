<?php

/**
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 */

namespace ConfigletTest\Unit\Config;

use \Configlet\MasterConfig;

class MasterConfigTest extends \PHPUnit_Framework_TestCase {

    public function testGettingModuleName() {
        $Config = new MasterConfig();
        $this->assertSame('master', $Config->getModuleName());
    }

    public function testSettingParameterWithNoModuleSeparatorAddsToApplicationConfig() {
        $Config = new MasterConfig();
        $Config['foo'] = 'bar';

        $AppStore = $Config[$Config::APP_MODULE];
        $this->assertInstanceOf('\\Configlet\\ImmutableProxyConfig', $AppStore);
        $this->assertSame('bar', $AppStore['foo']);
    }

    public function testSettingNewModuleParameterCreatesAppropriateModuleConfig() {
        $Config = new MasterConfig();
        $Config['foo.bar'] = 'foobar';
        $FooStore = $Config['foo'];
        $this->assertInstanceOf('\\Configlet\\ImmutableProxyConfig', $FooStore);
    }

    public function testSettingNewModuleParameterAlsoSetsAppropriateValueInModuleConfig() {
        $Config = new MasterConfig();
        $Config['foo.bar'] = 'foobar';
        $FooStore = $Config['foo'];
        $this->assertSame('foobar', $FooStore['bar']);
    }

    public function testSettingParameterWithNoModuleSeparatorReturnsSameValueWhenGetting() {
        $Config = new MasterConfig();
        $Config['foo'] = 'bar';
        $this->assertSame('bar', $Config['foo']);
    }

    public function testSettingModuleParameterBeforeAndAfterWritingModuleConfig() {
        $Config = new MasterConfig();
        $Config['module'] = 'configlet';
        $Config['module.foo'] = 'bar';
        $this->assertSame('configlet', $Config['app.module']);
        $this->assertSame('bar', $Config['module.foo']);
    }

    public function testSettingModuleParameterAfterModuleConfigurationSetThrowsException() {
        $Config = new MasterConfig();
        $Config['module.foo'] = 'configlet';
        $this->assertSame('configlet', $Config['module.foo']);

        $message = 'You may not set a parameter that shares a name with a module configuration';
        $this->setExpectedException('\\Configlet\\Exception\\IllegalConfigOperationException', $message);
        $Config['module'] = 'something';
    }

    public function testGettingModuleParameterNotSetReturnsNull() {
        $Config = new MasterConfig();
        $this->assertNull($Config['foo.bar']);
    }

    public function testGettingModuleParameterSetReturnsAppropriateValue() {
        $Config = new MasterConfig();
        $Config['foo.bar'] = 'foobar';
        $this->assertSame('foobar', $Config['foo.bar']);
    }

    public function testIssetOnAppParameterNotSetReturnsFalse() {
        $Config = new MasterConfig();
        $this->assertFalse(isset($Config['not-there']));
    }

    public function testIssetForModuleKeyThatHasBeenSetReturnsTrue() {
        $Config = new MasterConfig();
        $Config['configlet.foo'] = 'something';
        $this->assertSame('something', $Config['configlet.foo']);
        $this->assertTrue(isset($Config['configlet.foo']));
    }

    public function testIssetForModuleParameterBeforeAndAfterSettingModuleConfiguration() {
        $Config = new MasterConfig();
        $this->assertFalse(isset($Config['module']));
        $Config['module.param'] = 'foo';
        $this->assertTrue(isset($Config['module']));
    }

    public function testUnsettingSetValueDestroysAppropriateKey() {
        $Config = new MasterConfig();
        $Config['something'] = 'yea';
        $this->assertSame('yea', $Config['something']);
        unset($Config['something']);
        $this->assertNull($Config['something']);
    }

    public function testUnsettModuleParameterUnsetsModuleConfigurationNotAppConfiguration() {
        $Config = new MasterConfig();
        $Config['something'] = 'foo';
        $Config['module.something'] = 'bar';
        $this->assertSame('foo', $Config['something']);
        $this->assertSame('bar', $Config['module.something']);
        unset($Config['module.something']);
        $this->assertSame('foo', $Config['something']);
        $this->assertNull($Config['module.something']);
    }

    public function testUnsettingModuleConfigurationDestroysThatConfiguration() {
        $Config = new MasterConfig();
        $Config['module.foo'] = 'something';
        $this->assertSame('something', $Config['module.foo']);
        unset($Config['module']);
        $this->assertNull($Config['module']);
    }

    public function testUnsettingModuleConfigurationWithSimilarAppParameterNameDoesNotUnsetMasterConfig() {
        $Config = new MasterConfig();
        $Config['app.module'] = true;
        $Config['module.foo'] = 'something';

        $this->assertTrue($Config['app.module']);
        $this->assertSame('something', $Config['module.foo']);

        unset($Config['module']);

        // notice that with this test since we destroyed the 'module' module configuration
        // when we call this since the 'module' does not exist we change this to
        // 'app.module', thus accessing the appropriate value
        $this->assertTrue($Config['module']);
    }

    public function testChangingReturnTypeToMutableWhenRetrievingModuleConfig() {
        $Config = new MasterConfig();
        $Config['configlet.module_return_type'] = MasterConfig::MUTABLE;
        $this->assertInstanceOf('\\Configlet\\MutableConfig', $Config['configlet']);
    }

    public function testSwappingReturnTypeBackAndForthRetrievesAppropriateObjects() {
        $Config = new MasterConfig();
        $Config['configlet.module_return_type'] = MasterConfig::MUTABLE;
        $this->assertInstanceOf('\\Configlet\\MutableConfig', $Config['configlet']);
        $Config['configlet.module_return_type'] = MasterConfig::IMMUTABLE_PROXY;
        $this->assertInstanceOf('\\Configlet\\ImmutableProxyConfig', $Config['configlet']);
    }

    public function testFetchingBackToBackImmutableProxyReturnsCachedObject() {
        $Config = new MasterConfig();
        $Config['configlet.something'] = 'just getting module loaded';
        $this->assertInstanceOf('\\Configlet\\ImmutableProxyConfig', $first = $Config['configlet']);
        $second = $Config['configlet'];
        $this->assertSame($first, $second);
    }

    public function testChangingReturnTypeToImmutableGivesUsAnImmutableObject() {
        $Config = new MasterConfig();
        $Config['configlet.foo'] = 'something';
        $Config['configlet.module_return_type'] = MasterConfig::IMMUTABLE;

        $CfgltStore = $Config['configlet'];
        $this->assertInstanceOf('\\Configlet\\ImmutableConfig', $CfgltStore);
        $this->assertNotInstanceOf('\\Configlet\\ImmutableProxyConfig', $CfgltStore);
        $this->assertSame('configlet', $CfgltStore->getModuleName());
        $this->assertSame('something', $CfgltStore['foo']);
    }

    public function testIteratingOverConfigs() {
        $Config = new MasterConfig();
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

    public function testSettingModuleWithEmptyArrayThrowsException() {
        $Config = new MasterConfig();
        $message = 'A Configlet\\Config key must be a string and a \'array\' was given';
        $this->setExpectedException('\\Configlet\\Exception\\IllegalConfigOperationException', $message);
        $Config[[]] = 'something';
    }

}

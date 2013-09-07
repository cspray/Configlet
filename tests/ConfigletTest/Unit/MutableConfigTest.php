<?php

/**
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1
 * @since   0.1
 */

namespace ConfigletTest\Unit\Config;

use \Configlet\MutableConfig;

class MutableConfigTest extends \PHPUnit_Framework_TestCase {

    public function testSettingSimpleStringKeyAndValue() {
        $Config = new MutableConfig('foo');
        $Config['bar'] = 'foobar';

        $this->assertSame('foobar', $Config['bar']);
    }

    public function testSettingNonStringKeyThrowsException() {
        $Config = new MutableConfig('foo');
        $message = 'A Configlet\\Config key must be a string and a \'integer\' was given';
        $this->setExpectedException('\\Configlet\\Exception\\IllegalConfigOperationException', $message);
        $Config[1] = 'foo';
    }

    public function testSettingKeyThenUnsettingItProperlyDestroysConfiguration() {
        $Config = new MutableConfig('foo');
        $Config['bar'] = 'configlet';
        $this->assertSame('configlet', $Config['bar']);
        unset($Config['bar']);
        $this->assertNull($Config['bar']);
    }

}

<?php
/**
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
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
        $message = 'The key for a configuration MUST be a string value but \'integer\' was given';
        $this->setExpectedException('\\Configlet\\Exception\\IllegalConfigOperationException', $message);
        $Config[1] = 'foo';
    }


}

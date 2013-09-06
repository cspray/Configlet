<?php
/**
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 */

namespace ConfigletTest\Unit\Config;


use Configlet\ImmutableConfig;

class ImmutableConfigTest extends \PHPUnit_Framework_TestCase {

    public function testSettingStoreKeyValuePairResultsInAppropriateConfig() {
        $Config = new ImmutableConfig('foo', ['a' => 'b', 'b' => 'c', 'c' => 'd']);
        $this->assertSame($Config['a'], 'b');
        $this->assertSame($Config['b'], 'c');
        $this->assertSame($Config['c'], 'd');
    }


}

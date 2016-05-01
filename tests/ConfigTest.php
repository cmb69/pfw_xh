<?php

namespace Pfw;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        global $plugin_cf;

        $plugin_cf = array(
            'other' => array(
                'option1' => 'foo',
                'option2' => 'bar'
            ),
            'pfw' => array(
                'option1' => 'bar',
                'option3' => 'baz'
            )
        );
    }

    public function testConstructorIsPrivate()
    {
        $class = new \ReflectionClass('Pfw\\Config');
        $this->assertTrue($class->getConstructor()->isPrivate());
    }

    public function testExistingOption()
    {
        $subject = Config::instance('other');
        $this->assertTrue(isset($subject['option1']));
        $this->assertEquals('foo', $subject['option1']);
    }
    
    public function testInheritedOption()
    {
        $subject = Config::instance('other');
        $this->assertTrue(isset($subject['option3']));
        $this->assertEquals('baz', $subject['option3']);
    }

    public function testNonExistingOption()
    {
        $subject = Config::instance('other');
        $this->assertFalse(isset($subject['option4']));
        $this->assertNull($subject['option4']);
    }

    public function testNonExistingPlugin()
    {
        $this->assertNull(Config::instance('foo'));
    }

    public function testSetOptionThrows()
    {
        $this->setExpectedException('\\LogicException');
        Config::instance('other')['option1'] = 'foo';
    }

    public function testUnsetOptionThrows()
    {
        $this->setExpectedException('\\LogicException');
        unset(Config::instance('other')['option1']);
    }
}

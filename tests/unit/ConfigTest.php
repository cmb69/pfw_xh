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
        $this->assertEquals('foo', $subject->get('option1'));
    }
    
    public function testInheritedOption()
    {
        $subject = Config::instance('other');
        $this->assertEquals('baz', $subject->get('option3'));
    }

    public function testNonExistingOption()
    {
        $subject = Config::instance('other');
        $this->assertNull($subject->get('option4'));
    }

    public function testNonExistingPlugin()
    {
        $this->assertNull(Config::instance('foo'));
    }
}

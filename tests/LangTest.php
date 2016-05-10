<?php

namespace Pfw;

class LangTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        global $plugin_tx;

        $plugin_tx = array(
            'other' => array(
                'option1' => 'foo',
                'option2' => 'bar%s',
                'option4_singular' => '%d foo is %s',
                'option4_plural' => '%d foos are %s'
            ),
            'pfw' => array(
                'option1' => 'bar',
                'option3' => 'baz'
            )
        );
    }

    public function testConstructorIsPrivate()
    {
        //$class = new \ReflectionClass('Pfw\\Lang');
        //$this->assertTrue($class->getConstructor()->isPrivate());
    }

    public function testExistingOption()
    {
        $subject = Lang::instance('other');
        $this->assertEquals('foo', $subject->get('option1'));
    }

    public function testInheritedOption()
    {
        $subject = Lang::instance('other');
        $this->assertEquals('baz', $subject->get('option3'));
    }

    public function testNonExistingOption()
    {
        $subject = Lang::instance('other');
        $this->assertNull($subject->get('option4'));
    }

    public function testNonExistingPlugin()
    {
        $this->assertNull(Lang::instance('foo'));
    }

    public function testSingular()
    {
        $subject = Lang::instance('other');
        $this->assertEquals('barfoo', $subject->singular('option2', 'foo'));
    }
    
    public function testPlural()
    {
        $subject = Lang::instance('other');
        $this->assertEquals('0 foos are too few', $subject->plural('option4', 0, 'too few'));
        $this->assertEquals('1 foo is fine', $subject->plural('option4', 1, 'fine'));
        $this->assertEquals('3 foos are okay', $subject->plural('option4', 3, 'okay'));
        $this->assertEquals('5 foos are good', $subject->plural('option4', 5, 'good'));
    }
}

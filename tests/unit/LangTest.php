<?php

/*
Copyright 2016 Christoph M. Becker
 
This file is part of Pfw_XH.

Pfw_XH is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Pfw_XH is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Pfw_XH.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace Pfw;

class LangTest extends TestCase
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
        $subject = new Lang('other');
        $this->assertEquals('foo', $subject->get('option1'));
    }

    public function testInheritedOption()
    {
        $subject = new Lang('other');
        $this->assertEquals('baz', $subject->get('option3'));
    }

    public function testNonExistingOption()
    {
        $subject = new Lang('other');
        $this->assertNull($subject->get('option4'));
    }

    public function testSingular()
    {
        $subject = new Lang('other');
        $this->assertEquals('barfoo', $subject->singular('option2', 'foo'));
    }
    
    public function testPlural()
    {
        $subject = new Lang('other');
        $this->assertEquals('0 foos are too few', $subject->plural('option4', 0, 'too few'));
        $this->assertEquals('1 foo is fine', $subject->plural('option4', 1, 'fine'));
        $this->assertEquals('3 foos are okay', $subject->plural('option4', 3, 'okay'));
        $this->assertEquals('5 foos are good', $subject->plural('option4', 5, 'good'));
    }
}

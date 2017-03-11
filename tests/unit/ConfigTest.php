<?php

/*
 * Copyright 2016-2017 Christoph M. Becker
 *
 * This file is part of Pfw_XH.
 *
 * Pfw_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Pfw_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Pfw_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Pfw;

class ConfigTest extends TestCase
{
    public function setUp()
    {
        global $plugin_cf;

        parent::setUp();
        $plugin_cf = array(
            'other' => ['option1' => 'foo', 'option2' => 'bar'],
            'pfw' => ['option1' => 'bar', 'option3' => 'baz']
        );
    }

    public function testExistingOption()
    {
        $subject = new Config('other');
        $this->assertEquals('foo', $subject->get('option1'));
    }
    
    public function testInheritedOption()
    {
        $subject = new Config('other');
        $this->assertEquals('baz', $subject->get('option3'));
    }

    public function testNonExistingOption()
    {
        $subject = new Config('other');
        $this->assertNull($subject->get('option4'));
    }
}

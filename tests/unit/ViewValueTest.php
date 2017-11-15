<?php

/*
 * Copyright 2017 Christoph M. Becker
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

use PHPUnit\Framework\TestCase;
use stdClass;
use Pfw\View\View;
use Pfw\View\ViewValue;
use Pfw\View\ArrayViewValue;
use Pfw\View\ObjectViewValue;

class ViewValueTest extends TestCase
{
    /**
     * @var View
     */
    private $viewMock;

    /**
     * @return void
     */
    protected function setUp()
    {
        uopz_flags(View::class, null, 0); // un-final-ize class
        $this->viewMock = $this->createMock(View::class);
    }

    /**
     * @return void
     */
    protected function tearDown()
    {
        uopz_flags(View::class, null, 4); // finalize class again
    }

    /**
     * @return void
     * @covers Pfw\View\ViewValue::create
     */
    public function testBoolValue()
    {
        $this->assertInternalType('bool', ViewValue::create($this->viewMock, false));
    }

    /**
     * @return void
     * @covers Pfw\View\ViewValue::create
     */
    public function testArrayValue()
    {
        $this->assertInstanceOf(ArrayViewValue::class, ViewValue::create($this->viewMock, []));
    }

    /**
     * @return void
     * @covers Pfw\View\ViewValue::create
     */
    public function testObjectValue()
    {
        $this->assertInstanceOf(ObjectViewValue::class, ViewValue::create($this->viewMock, new stdClass));
    }

    /**
     * @return void
     * @covers Pfw\View\ViewValue::create
     */
    public function testStringValue()
    {
        $this->assertInstanceOf(ViewValue::class, ViewValue::create($this->viewMock, ''));
    }

    /**
     * @return void
     */
    public function testToStringCallsEscape()
    {
        $this->viewMock->expects($this->once())->method('escape')->willReturn('foo');
        (string) new ViewValue($this->viewMock, 'foo');
    }
}

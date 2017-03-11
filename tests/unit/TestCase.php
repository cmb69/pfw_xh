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

/**
 * Test cases for the plugin framework.
 *
 * To make sure we never test with the real system, TestCase::setUp()
 * creates a fake system for us. That implies that we have to call 
 * parent::setUp() if we override setUp() in individual test cases.
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        System::loadInstance(new FakeSystem());
    }
    
    /**
     * Defines a constant.
     *
     * If the constant is already defined, it is redefined to the new value.
     */
    protected function defineConstant($name, $value)
    {
        if (defined($name)) {
            runkit_constant_redefine($name, $value);
        } else {
            define($name, $value);
        }
    }
}

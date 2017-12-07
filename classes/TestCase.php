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

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use PHPUnit_Extensions_MockFunction;
use PHPUnit_Extensions_MockStaticMethod;

abstract class TestCase extends PHPUnitTestCase
{
    /**
     * Mocks a function in the scope of an object.
     *
     * @param string $function
     * @param object $scopeObject
     * @return PHPUnit_Extensions_MockFunction
     */
    protected function mockFunction($function, $scopeObject = null)
    {
        return new PHPUnit_Extensions_MockFunction($function, $scopeObject);
    }

    /**
     * Mocks a static method in the scope of an object.
     *
     * @param string $class
     * @param string $method
     * @param object $scopeObject
     * @return PHPUnit_Extensions_MockStaticMethod
     */
    protected function mockStaticMethod($class, $method, $scopeObject = null)
    {
        return new PHPUnit_Extensions_MockStaticMethod("$class::$method", $scopeObject);
    }

    /**
     * (Re)defines a constant.
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    protected function setConstant($name, $value)
    {
        if (defined($name)) {
            runkit_constant_redefine($name, $value);
        } else {
            define($name, $value);
        }
    }
}

<?php

/*
Copyright 2016-2017 Christoph M. Becker
 
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

class SytemTest extends TestCase
{
    public function setUp()
    {
        System::loadInstance(null);
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testCallingUndefinedStaticMethodThrows()
    {
        System::foo();
    }

    public function testRequestReturnsSameInstance()
    {
        $this->assertSame(System::getRequest(), System::getRequest());
    }

    public function testResponseReturnsSameInstance()
    {
        $this->assertSame(System::getResponse(), System::getResponse());
    }

    public function testPluginReturnsRegisteredPlugin()
    {
        $this->assertSame(System::registerPlugin('foo'), System::getPlugin('foo'));
    }

    public function testConfigReturnsSameInstance()
    {
        $this->assertSame(System::getConfig('foo'), System::getConfig('foo'));
    }

    public function testConfigReturnsIndividualInstances()
    {
        $this->assertNotSame(System::getConfig('foo'), System::getConfig('bar'));
    }

    public function testLangReturnsSameInstance()
    {
        $this->assertSame(System::getLang('foo'), System::getLang('foo'));
    }

    public function testLangReturnsIndividualInstances()
    {
        $this->assertNotSame(System::getLang('foo'), System::getLang('bar'));
    }
    
    public function testRunsPlugins()
    {
        $pluginFilesStub = new \PHPUnit_Extensions_MockFunction('pluginFiles', null);
        $pluginMenuStub = new \PHPUnit_Extensions_MockFunction('pluginMenu', null);
        System::registerPlugin('foo');
        System::runPlugins();
        $pluginFilesStub->restore();
        $pluginMenuStub->restore();
    }
}
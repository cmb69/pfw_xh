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
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class PluginTest extends TestCase
{
    private $subject;

    private $registerPluginMenuItemMock;

    private $pluginFiles;
    
    public function setUp()
    {
        global $pth;

        parent::setUp();
        $pth = array(
            'folder' => ['plugin' => './plugins/pfw/'],
            'file' => ['plugin_help' => 'foo']
        );
        $this->subject = new Plugin('pfw');
        $this->registerPluginMenuItemMock = new \PHPUnit_Extensions_MockFunction(
            'XH_registerPluginMenuItem',
            $this->subject
        );
        $this->pluginFiles = new \PHPUnit_Extensions_MockFunction(
            'pluginFiles',
            $this->subject
        );
    }

    public function testGetName()
    {
        $this->assertEquals('pfw', $this->subject->getName());
    }

    public function testGetFolder()
    {
        $this->assertEquals('./plugins/pfw/', $this->subject->getFolder());
    }

    public function testCopyright()
    {
        $copyright = '2016-2017 Christoph M. Becker';
        $this->subject->copyright($copyright);
        $this->assertEquals($copyright, $this->subject->getCopyright());
    }

    public function testVersion()
    {
        $this->subject->version('1.0');
        $this->assertEquals('1.0', $this->subject->getVersion());
    }
    
    public function testRouteReturnsSelf()
    {
        $this->assertSame(
            $this->subject,
            $this->subject->route(['foo' => 'Bar'])
        );
    }
    
    public function testAdminReturnsSelf()
    {
        $this->assertSame(
            $this->subject,
            $this->subject->admin()
        );
    }

    public function testAdminRouteReturnsSelf()
    {
        $this->assertSame(
            $this->subject,
            $this->subject->admin()->route(['foo' => 'Bar'])
        );
    }

    public function testFuncReturnsSelf()
    {
        $this->assertSame(
            $this->subject,
            $this->subject->func('pfw_foo')
        );
    }
    
    public function testRegistersFunctionNames()
    {
        $this->subject->func('pfw_foo');
        $this->assertEquals(['pfw_foo'], $this->subject->getFuncNames());
    }
    
    public function testRegistersFunctionRoutes()
    {
        $this->subject->func('pfw_foo')->route(['?foo' => 'Bar']);
        $routes = $this->subject->getFuncRoutes('pfw_foo'); 
        $this->assertContainsOnlyInstancesOf('Pfw\\Route', $routes);
        $this->assertCount(1, $routes);
    }
    
    public function testRunDefinesFunction()
    {
        $this->subject->func('pfw_foo')->run();
        $this->assertTrue(function_exists('pfw_foo'));
    }
    
    public function testRunInAdminModeCallsRegisterMenuItems()
    {
        $this->defineConstant('XH_ADM', true);
        $this->registerPluginMenuItemMock->expects($this->exactly(1));
        $pluginMenuMock = new \PHPUnit_Extensions_MockFunction('pluginMenu', $this->subject);
        $pluginMenuMock->expects($this->never());
        $this->subject->admin()
            ->route([$this->subject->getName() => ''])
            ->run();
    }
}

class TestAdminController
{
    public static $testCount = 0;

    public function handleTest()
    {
        self::$testCount++;
    }
}

class DefaultFooPageController
{
    public static $testCount = 0;

    public function handleDefault()
    {
        self::$testCount++;
    }
}

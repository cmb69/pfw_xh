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

require_once '../../cmsimple/functions.php';
require_once '../../cmsimple/adminfuncs.php';

class PluginTest extends \PHPUnit_Framework_TestCase
{
    private $registerStandardPluginMenuItemsMock;

    private $subject;

    public function setUp()
    {
        global $plugin, $pth;

        $plugin = 'pfw';
        $pth = array(
            'folder' => array(
                'plugin' => './plugins/pfw/'
            )
        );
        $this->subject = Plugin::register();
        $this->registerStandardPluginMenuItemsMock = new \PHPUnit_Extensions_MockFunction(
            'XH_registerStandardPluginMenuItems',
            $this->subject
        );
    }

    public function testRegistersTheInstance()
    {
        $this->assertSame($this->subject, Plugin::instance('pfw'));
    }

    public function testName()
    {
        $this->assertEquals('pfw', $this->subject->name);
    }

    public function testFolder()
    {
        $this->assertEquals('./plugins/pfw/', $this->subject->folder);
    }

    public function testCopyright()
    {
        $copyright = '2016 Christoph M. Becker';
        $this->subject->copyright($copyright);
        $this->assertEquals($copyright, $this->subject->copyright);
    }

    public function testVersion()
    {
        $this->subject->version('1.0');
        $this->assertEquals('1.0', $this->subject->version);
    }

    public function testFuncReturnsSelf()
    {
        $this->assertSame(
            $this->subject,
            $this->subject->func('pfw_foo')
        );
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

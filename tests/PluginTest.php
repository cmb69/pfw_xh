<?php

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

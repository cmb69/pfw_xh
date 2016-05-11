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

    public function testMenuShownInAdminMode()
    {
        $this->defineConstant('XH_ADM', true);
        $this->registerStandardPluginMenuItemsMock->expects($this->once());
        $this->subject->admin();
    }

    public function testMenuOnlyShownInAdminMode()
    {
        $this->defineConstant('XH_ADM', false);
        $this->registerStandardPluginMenuItemsMock->expects($this->never());
        $this->subject->admin();
    }

    public function testTestAdministration()
    {
        global $pfw, $admin, $action;

        $this->defineConstant('XH_ADM', true);
        $pfw = 'true';
        $admin = 'plugin_test';
        $action = 'plugin_test';
        $this->subject->admin();
        $this->assertEquals(1, TestAdminController::$testCount);
    }

    public function testDefaultFunction()
    {
        $this->subject->func();
        $this->assertInternalType('callable', 'pfw');
        runkit_function_remove('pfw');
    }

    public function testFuncDefinesFunction()
    {
        $this->subject->func('foo');
        $this->assertInternalType('callable', 'pfw_foo');
        runkit_function_remove('pfw_foo');
    }

    public function testFuncReturnsSelf()
    {
        $this->assertSame(
            $this->subject,
            $this->subject->func()
        );
    }

    public function testPageReturnsSelf()
    {
        //$this->assertSame(
            //$this->subject, $this->subject->page('foo')
        //);
    }

    public function testPageCallAction()
    {
        global $su;

        $su = 'foo';
        //$this->subject->page('foo');
        //$this->assertEquals(1, DefaultFooPageController::$testCount);
    }

    private function defineConstant($name, $value)
    {
        if (defined($name)) {
            runkit_constant_redefine($name, $value);
        } else {
            define($name, $value);
        }
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

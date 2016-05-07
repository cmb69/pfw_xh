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
        $this->subject = new Plugin();
        $this->registerStandardPluginMenuItemsMock = new \PHPUnit_Extensions_MockFunction(
            'XH_registerStandardPluginMenuItems',
            $this->subject
        );
    }

    public function testName()
    {
        $this->assertEquals('pfw', $this->subject->name());
    }

    public function testFolder()
    {
        $this->assertEquals('./plugins/pfw/', $this->subject->folder());
    }

    public function testVersion()
    {
        $this->subject->version('1.0');
        $this->assertEquals('1.0', $this->subject->version());
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

    public function testDefaultAdministration()
    {
        global $pfw, $admin, $action;

        $this->defineConstant('XH_ADM', true);
        $pfw = 'true';
        $admin = '';
        $action = '';
        $this->subject->admin();
        $this->assertEquals(1, DefaultAdminController::$defaultCount);
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


class DefaultAdminController
{
    public static $defaultCount = 0;
    
    public function handleDefault()
    {
        self::$defaultCount++;
    }
}

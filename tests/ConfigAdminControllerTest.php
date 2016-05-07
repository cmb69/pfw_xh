<?php

/**
 * The plugin framework
 */
namespace Pfw {

use org\bovigo\vfs\vfsStream;

class ConfigAdminControllerTest extends \PHPUnit_Framework_TestCase
{
    private $subject;

    public function setUp()
    {
        global $pth;

        $root = vfsStream::setup();
        $pth = array(
            'folder' => array(
                'classes' => $root->url() . '/'
            )
        );
        touch("{$pth['folder']['classes']}FileEdit.php");
        $this->subject = new ConfigAdminController();
    }

    public function testEdit()
    {
        $this->subject->handleEdit();
        $this->assertEquals(1, \XH_PluginConfigFileEdit::$formCount);
    }
    
    public function testSave()
    {
        $this->subject->handleSave();
        $this->assertEquals(1, \XH_PluginConfigFileEdit::$submitCount);
    }
}

}

namespace {

class XH_PluginConfigFileEdit
{
    public static $formCount = 0;

    public static $submitCount = 0;

    public function form()
    {
        self::$formCount++;
    }
    
    public function submit()
    {
        self::$submitCount++;
    }
}

}

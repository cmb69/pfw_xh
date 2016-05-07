<?php

/**
 * The plugin framework
 */
namespace Pfw {

use org\bovigo\vfs\vfsStream;

class StylesheetAdminControllerTest extends \PHPUnit_Framework_TestCase
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
        $this->subject = new StylesheetAdminController();
    }

    public function testText()
    {
        $this->subject->handleText();
        $this->assertEquals(1, \XH_PluginTextFileEdit::$formCount);
    }
    
    public function testTextsave()
    {
        $this->subject->handleTextsave();
        $this->assertEquals(1, \XH_PluginTextFileEdit::$submitCount);
    }
}

}

namespace {

class XH_PluginTextFileEdit
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

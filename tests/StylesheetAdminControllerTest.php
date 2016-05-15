<?php

/**
 * The plugin framework
 */
namespace Pfw;

class StylesheetAdminControllerTest extends \PHPUnit_Framework_TestCase
{
    private $plugin;

    private $fileEdit;

    private $subject;

    public function setUp()
    {
        $this->plugin = $this->getMockBuilder('Pfw\\Plugin')
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject = $this->getMockBuilder('Pfw\\StylesheetAdminController')
            ->setConstructorArgs([$this->plugin])
            ->setMethods(['createFileEdit', 'url'])
            ->getMock();
        $this->fileEdit = $this->getMockBuilder('XH_PluginTextFileEdit')
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject
            ->expects($this->any())
            ->method('createFileEdit')
            ->willReturn($this->fileEdit);
    }

    public function testTextAction()
    {
        $this->fileEdit->expects($this->once())->method('form');
        $this->subject->plugin_textAction();
    }
    
    public function testTextsaveAction()
    {
        $this->fileEdit->expects($this->once())->method('submit');
        $this->subject->plugin_textsaveAction();
    }

    public function testDispatcher()
    {
        $this->assertEquals('action', $this->subject->getDispatcher());
    }
}

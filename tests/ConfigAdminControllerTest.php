<?php

/**
 * The plugin framework
 */
namespace Pfw;

class ConfigAdminControllerTest extends \PHPUnit_Framework_TestCase
{
    private $plugin;

    private $fileEdit;

    private $subject;

    public function setUp()
    {
        $this->plugin = $this->getMockBuilder('Pfw\\Plugin')
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject = $this->getMockBuilder('Pfw\\ConfigAdminController')
            ->setConstructorArgs([$this->plugin])
            ->setMethods(['createFileEdit', 'url'])
            ->getMock();
        $this->fileEdit = $this->getMockBuilder('XH_PluginConfigFileEdit')
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject
            ->expects($this->any())
            ->method('createFileEdit')
            ->willReturn($this->fileEdit);
    }

    public function testEditAction()
    {
        $this->fileEdit->expects($this->once())->method('form');
        $this->subject->plugin_editAction();
    }

    public function testSaveAction()
    {
        $this->fileEdit->expects($this->once())->method('submit');
        $this->subject->plugin_saveAction();
    }
    
    public function testDispatcher()
    {
        $this->assertEquals('action', $this->subject->getDispatcher());
    }
}

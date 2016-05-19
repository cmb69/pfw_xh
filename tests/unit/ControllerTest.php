<?php

namespace Pfw;

class ControllerTest extends \PHPUnit_Framework_TestCase
{
    private $plugin;

    private $subject;

    public function setUp()
    {
        global $pth;

        $pth = array(
            'folder' => array(
                'content' => 'foo/bar/baz/'
            )
        );
        $this->plugin = $this->getMockBuilder('Pfw\\Plugin')
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject = $this->getMockBuilder('Pfw\\Controller')
            ->setConstructorArgs([$this->plugin])
            ->setMethods(null)
            ->getMock();
    }

    public function testDispatcher()
    {
        $this->assertNull($this->subject->getDispatcher());
    }

    public function testPlugin()
    {
        $this->assertSame($this->plugin, $this->subject->plugin());
    }
    
    public function testContentFolder()
    {
        $this->assertEquals('foo/bar/baz/', $this->subject->contentFolder());
    }
}

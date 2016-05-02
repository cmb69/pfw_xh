<?php

namespace Pfw;

class PluginTest extends \PHPUnit_Framework_TestCase
{
    private $subject;

    public function setUp()
    {
        global $plugin, $pth;

        $plugin = 'foo';
        $pth = array(
            'folder' => array(
                'plugin' => './plugins/foo/'
            )
        );
        $this->subject = new Plugin();
    }

    public function testName()
    {
        $this->assertEquals('foo', $this->subject->name());
    }

    public function testFolder()
    {
        $this->assertEquals('./plugins/foo/', $this->subject->folder());
    }

    public function testVersion()
    {
        $this->assertEquals('0.1.0', $this->subject->version());
    }
}

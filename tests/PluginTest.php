<?php

namespace Pfw;

class PluginTest extends \PHPUnit_Framework_TestCase
{
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
        $this->subject = (new Plugin())
            ->version('1.0');
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
        $this->assertEquals('1.0', $this->subject->version());
    }
}

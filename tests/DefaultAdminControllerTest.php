<?php

namespace Pfw;

class DefaultAdminControllerTest extends \PHPUnit_Framework_TestCase
{
    private $subject;

    private $langInst;

    public function setUp()
    {
        $plugin = $this->getMockBuilder('Pfw\\Plugin')
            ->disableOriginalConstructor()
            ->getMock();
        $plugin->expects($this->any())->method('version')->willReturn('0.1');
        $plugin->expects($this->any())->method('folder')->willReturn('./');
        $plugin->expects($this->any())->method('name')->willReturn('pfw');
        $plugin->expects($this->any())->method('copyright')->willReturn('2016 cmb');
        $plugin->expects($this->any())->method('functions')->willReturn(array());
        $this->subject = new DefaultAdminController($plugin);
        $lang = $this->getMockBuilder('Pfw\\Lang')->disableOriginalConstructor()->getMock();
        $this->langInst = new \PHPUnit_Extensions_MockStaticMethod('Pfw\\Lang::instance', null);
        $this->langInst->expects($this->any())->willReturn($lang);
    }

    public function tearDown()
    {
        $this->langInst->restore();
    }

    public function testDefaultAction()
    {
/*
        $this->subject->handleDefault();
        $this->expectOutputString(<<<EOT
<h1>Pfw</h1>
<img class="pfw_logo" src="./pfw.png"/>
<p>Version 0.1</p>
<p>Copyright 2016 cmb</p>
<p class="pfw_license">This program is free software: you can redistribute it
and/or modify it under the terms of the GNU General Public License as published
by the Free Software Foundation, either version 3 of the License, or (at your
option) any later version.</p>
<p class="pfw_license">This program is distributed in the hope that it will be
useful, but <em>without any warranty</em>; without even the implied warranty of
<em>merchantability</em> or <em>fitness for a particular purpose</em>. See the
GNU General Public License for more details.</p>
<p class="pfw_license">You should have received a copy of the GNU General
Public License along with this program. If not, see <a
href="http://www.gnu.org/licenses/">http://www.gnu.org/licenses/</a>.</p>
<h4></h4>
<p><img src="core/css/success.png" alt=""> </p>
<p><img src="core/css/success.png" alt=""> </p>
<p><img src="core/css/failure.png" alt=""> </p><h4></h4>
<dl>
</dl>

EOT
        );
*/
    }

    public function testContentFolder()
    {
        global $pth;

        $pth = array(
            'folder' => array('content' => 'foobar')
        );
        $this->assertEquals('foobar', $this->subject->contentFolder());
    }
}

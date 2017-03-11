<?php

/*
Copyright 2016-2017 Christoph M. Becker
 
This file is part of Pfw_XH.

Pfw_XH is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Pfw_XH is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Pfw_XH.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace Pfw;

class ControllerTest extends TestCase
{
    private $plugin;

    private $subject;
    
    public function setUp()
    {
        global $pth;

        parent::setUp();
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
    
    public function testSeeOtherCallsRedirect()
    {
        System::response()->expects($this->any())->method('redirect')
            ->with($this->equalTo('absolute'), $this->equalTo(303));
        $url = $this->getMockBuilder('Pfw\\Url')
            ->disableOriginalConstructor()
            ->getMock();
        $url->expects($this->once())->method('absolute')->willReturn('absolute');
        $this->subject->seeOther($url);
    }
    
    public function testUrl()
    {
        $url = $this->getMockBuilder('Pfw\\Url')
            ->disableOriginalConstructor()
            ->getMock();
        $url->expects($this->once())->method('with');
        System::request()->expects($this->once())->method('url')->willReturn($url);
        $this->subject->url('foo');
    }
}

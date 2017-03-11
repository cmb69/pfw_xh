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

use org\bovigo\vfs\vfsStream;

class HtmlViewTest extends TestCase
{
    private $subject;
    
    private $root;
    
    public function setUp()
    {
        parent::setUp();
        $this->root = vfsStream::setup();
        $controller = $this->getMockBuilder('Pfw\\Controller')
            ->disableOriginalConstructor()
            ->getMock();
        $plugin = $this->getMockBuilder('Pfw\\Plugin')
            ->disableOriginalConstructor()
            ->getMock();
        $lang = $this->getMockBuilder('Pfw\\Lang')
            ->disableOriginalConstructor()
            ->getMock();
        $plugin->expects($this->any())->method('lang')
            ->willReturn($lang);
        $plugin->expects($this->any())->method('folder')
            ->willReturn($this->root->url() . '/');
        $controller->expects($this->any())->method('plugin')->willReturn($plugin);
        $this->subject = new HtmlView($controller, 'foo');
    }

    /**
     * @expectedException PHPUnit_Framework_Error_Warning
     */
    public function testIllegalPropertyName()
    {
        $this->subject->data = 'foo';
    }

    /**
     * @expectedException PHPUnit_Framework_Error_Warning
     */
    public function testIllegalMethodName()
    {
        $this->subject->escape = 'trim';
    }
    
    public function testEscapesStrings()
    {
        $this->setUpTemplate('<?php echo $this->escape($this->foo)? >');
        $this->subject->foo = '<"&>';
        $this->expectOutputString('&lt;&quot;&amp;&gt;');
        $this->subject->render();
    }

    public function testDoesNotEscapeProperties()
    {
        $this->setUpTemplate('<?php echo $this->foo? >');
        $this->subject->foo = '<"&>';
        $this->expectOutputString('<"&>');
        $this->subject->render();
    }

    public function testAutoEscapesMethods()
    {
        $this->setUpTemplate('<?php echo $this->foo()? >');
        $this->subject->foo = '<"&>';
        $this->expectOutputString('&lt;&quot;&amp;&gt;');
        $this->subject->render();
    }

    public function testDoesNotEscapeHtmlStrings()
    {
        $this->setUpTemplate('<?php echo $this->escape($this->foo)? >');
        $this->subject->foo = new HtmlString('<"&>');
        $this->expectOutputString('<"&>');
        $this->subject->render();
    }
    
    private function setUpTemplate($contents)
    {
        $viewFolder = $this->root->url() . DIRECTORY_SEPARATOR . 'views';
        mkdir($viewFolder);
        file_put_contents("$viewFolder/foo.php", str_replace('? >', '?>', $contents));
    }
}

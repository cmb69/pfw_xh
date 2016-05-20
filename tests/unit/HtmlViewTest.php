<?php

/*
Copyright 2016 Christoph M. Becker
 
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

class HtmlViewTest extends \PHPUnit_Framework_TestCase
{
    private $subject;
    
    private $root;
    
    private $template = <<<EOT
<?php echo \$foo? >
<?php echo \$this->text('foo')? >
<?php echo \$this->plural('bar', 3)? >
EOT;

    private $output = <<<EOT
foo
EOT;
    
    public function setUp()
    {
        $this->root = vfsStream::setup();
        $viewFolder = $this->root->url() . DIRECTORY_SEPARATOR . 'views';
        mkdir($viewFolder);
        file_put_contents("$viewFolder/foo.php", str_replace('? >', '?>', $this->template));
        $controller = $this->getMockBuilder('Pfw\\Controller')
            ->disableOriginalConstructor()
            ->getMock();
        $plugin = $this->getMockBuilder('Pfw\\Plugin')
            ->disableOriginalConstructor()
            ->getMock();
        $lang = $this->getMockBuilder('Pfw\\Lang')
            ->disableOriginalConstructor()
            ->getMock();
        $map = array(
            array('folder', $this->root->url() . '/'),
            array('lang', $lang)
        );
        $plugin->expects($this->any())->method('__get')
            ->will($this->returnValueMap($map));
        $controller->expects($this->any())->method('plugin')->willReturn($plugin);
        $this->subject = new HtmlView($controller, 'foo');
    }
    
    public function testRender()
    {
        $this->subject->foo = 'foo';
        $this->expectOutputString($this->output);
        $this->subject->render();
    }
}

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

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    private $subject;

    public function setUp()
    {
        $this->subject = Response::instance();
    }

    public function testConstructorIsPrivate()
    {
        $class = new \ReflectionClass('Pfw\\Response');
        $this->assertTrue($class->getConstructor()->isPrivate());
    }

    public function testAppend()
    {
        global $o;

        $o = 'before';
        $this->subject->append('appended');
        $this->assertEquals('beforeappended', $o);
    }

    public function testAppendToHead()
    {
        global $hjs;

        $hjs = 'before';
        $this->subject->appendToHead('appended');
        $this->assertEquals('beforeappended', $hjs);
    }

    public function testAppendToBody()
    {
        global $bjs;

        $bjs = 'before';
        $this->subject->appendToBody('appended');
        $this->assertEquals('beforeappended', $bjs);
    }

    public function testAddStylesheet()
    {
        global $hjs;

        $hjs = 'before';
        $path = './plugins/pfw/stylesheet.css';
        $this->subject->addStylesheet($path);
        $this->assertEquals(
            sprintf('before<link rel="stylesheet" type="text/css" href="%s">', $path),
            $hjs
        );
    }

    public function testAddcript()
    {
        global $bjs;

        $bjs = 'before';
        $path = './plugins/pfw/javascript.js';
        $this->subject->addScript($path);
        $this->assertEquals(
            sprintf('before<script type="text/javascript" src="%s"', $path),
            $bjs
        );
    }

    public function testSetTitle()
    {
        global $title;

        $this->subject->setTitle('Pfw_XH');
        $this->assertEquals('Pfw_XH', $title);
    }

    public function testRedirect()
    {
        $exit = new \PHPUnit_Extensions_MockFunction('XH_exit', $this->subject);
        $exit->expects($this->once());
        $header = new \PHPUnit_Extensions_MockFunction('header', $this->subject);
        $header->expects($this->once())->with(
            $this->equalTo('Location: http://example.com'),
            302
        );
        $this->subject->redirect('http://example.com');
    }
}

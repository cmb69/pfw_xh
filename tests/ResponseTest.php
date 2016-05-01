<?php

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
}

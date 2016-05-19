<?php

namespace Pfw;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorIsPrivate()
    {
        $class = new \ReflectionClass('Pfw\\Request');
        $this->assertTrue($class->getConstructor()->isPrivate());
    }

    public function testMethod()
    {
        $subject = Request::instance();
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertEquals($_SERVER['REQUEST_METHOD'], $subject->method());
    }

    public function testUrl()
    {
        global $sn;

        $sn = '/xh/';
        $_SERVER['QUERY_STRING'] = 'foo=bar';
        $subject = Request::instance();
        $this->assertEquals('/xh/?foo=bar', $subject->url());
    }
}

<?php

namespace Pfw;

class RouteTest extends \PHPUnit_Framework_TestCase
{
    private $plugin;

    private $controller;

    public function setUp()
    {
        $this->plugin = Plugin::register();
        $this->plugin = $this->getMockBuilder('Pfw\\Plugin')
            ->disableOriginalConstructor()
            ->getMock();
        $this->plugin
            ->expects($this->any())
            ->method('__get')
            ->willReturn('pfw');
        $this->controller = $this->getMockBuilder('Pfw\\FooController')
            ->disableOriginalConstructor()
            ->getMock();
        $this->controller->expects($this->any())
            ->method('getDispatcher')
            ->willReturn('action');
    }

    public function testSimpleRoute()
    {
        $_GET = [
            'admin' => 'foo'
        ];
        $route = $this->createSubject(['admin=foo' => 'Pfw\\FooController']);
        $this->controller
            ->expects($this->once())
            ->method('indexAction');
        $route->resolve();
    }

    public function testNonMatchingSimpleRoute()
    {
        $_GET = [
            'admin' => 'bar'
        ];
        $route = $this->createSubject(['admin=foo' => 'Pfw\\FooController']);
        $this->controller
            ->expects($this->never())
            ->method('indexAction');
        $route->resolve();
    }

    public function testPageRoute()
    {
        global $su;

        $su = 'foo';
        $route = $this->createSubject(['?foo' => 'Pfw\\FooController']);
        $this->controller
            ->expects($this->once())
            ->method('indexAction');
        $route->resolve();
    }

    public function testNonMatchingPageRoute()
    {
        global $su;

        $su = 'bar';
        $route = $this->createSubject(['?foo' => 'Pfw\\FooController']);
        $this->controller
            ->expects($this->never())
            ->method('indexAction');
        $route->resolve();
    }

    public function testFirstMatchWins()
    {
        $_GET = [
            'admin' => 'foo'
        ];
        $route = $this->createSubject([
            'admin=foo' => 'Pfw\\FooController',
            'admin' => 'Pfw\\BarController' // this would raise an error
        ]);
        $this->controller
            ->expects($this->once())
            ->method('indexAction');
        $route->resolve();
    }

    public function testDispatchesOnActionWithParams()
    {
        $_GET = [
            'action' => 'bar',
            'pfw_id' => '42'
        ];
        $route = $this->createSubject(['' => 'Pfw\\FooController']);
        $this->controller
            ->expects($this->once())
            ->method('barAction')
            ->with($this->equalTo('42'), $this->isNull());
        $route->resolve();
    }

    private function createSubject(array $map)
    {
        $route = $this->getMockBuilder('Pfw\\Route')
            ->setConstructorArgs([$this->plugin, $map])
            ->setMethods(['createController'])
            ->getMock();
        $route->expects($this->any())
            ->method('createController')
            ->willReturn($this->controller);
        return $route;
    }
}

class FooController extends Controller
{
    const DISPATCHER = 'action';

    public function indexAction()
    {
    }

    public function barAction($id, $baz)
    {
    }
}

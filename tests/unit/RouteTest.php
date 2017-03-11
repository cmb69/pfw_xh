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

class RouteTest extends TestCase
{
    private $plugin;

    private $controller;

    public function setUp()
    {
        parent::setUp();
        $this->plugin = $this->getMockBuilder('Pfw\\Plugin')
            ->disableOriginalConstructor()
            ->getMock();
        $this->plugin
            ->expects($this->any())
            ->method('name')
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

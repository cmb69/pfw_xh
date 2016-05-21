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

class FakeSystem extends \PHPUnit_Framework_TestCase
{
    private $request;
    
    private $response;
    
    private $plugins = array();
    
    private $configs = array();
    
    private $lang = array();
    
    public function request()
    {
        if (!isset($this->request)) {
            $this->request = $this->getMockBuilder('Pfw\\Request')
                ->disableOriginalConstructor()
                ->getMock();
        }
        return $this->request;
    }
    
    public function response()
    {
        if (!isset($this->response)) {
            $this->response = $this->getMockBuilder('Pfw\\Response')
                ->disableOriginalConstructor()
                ->getMock();
        }
        return $this->response;
    }
    
    public function plugin($name)
    {
        if (!isset($this->plugins[$name])) {
            $this->plugins[$name] = $this->getMockBuilder('Pfw\\Plugin')
                ->disableOriginalConstructor()
                ->getMock();
        }
        return $this->plugins[$name];
    }

    public function config($name)
    {
        if (!isset($this->config[$name])) {
            $this->config[$name] = $this->getMockBuilder('Pfw\\Lang')
                ->disableOriginalConstructor()
                ->getMock();
        }
        return $this->config[$name];
    }
    
    public function lang($name)
    {
        if (!isset($this->lang[$name])) {
            $this->lang[$name] = $this->getMockBuilder('Pfw\\Lang')
                ->disableOriginalConstructor()
                ->getMock();
        }
        return $this->lang[$name];
    }
    
    public function registerPlugin()
    {
        
    }
    
    public function runPlugins()
    {
        
    }
}
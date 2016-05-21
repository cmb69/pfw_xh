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

class UrlTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->defineConstant('CMSIMPLE_URL', 'http://localhost/xh/');
    }

    public function testRelative()
    {
        $this->assertEquals(
            '/xh/?pagemanager&admin=plugin_config&action=plugin_save',
            $this->pagemanagerConfigUrl()->relative()
        );
    }

    public function testAbsolute()
    {
        $this->assertEquals(
            'http://localhost/xh/?pagemanager&admin=plugin_config&action=plugin_save',
            $this->pagemanagerConfigUrl()->absolute()
        );
    }

    public function testToString()
    {
        $this->assertEquals(
            '/xh/?pagemanager&admin=plugin_config&action=plugin_save',
            $this->pagemanagerConfigUrl()
        );
    }

    public function testWithReturnsNewInstance()
    {
        $url = $this->pagemanagerConfigUrl();
        $this->assertNotSame($url, $url->with('action', 'plugin_edit'));
    }

    public function testWith()
    {
        $url = $this->pagemanagerConfigUrl();
        $this->assertEquals(
            '/xh/?pagemanager&admin=plugin_config&action=plugin_edit',
            $url->with('action', 'plugin_edit')
        );
    }

    public function testWithout()
    {
        $url = $this->pagemanagerConfigUrl();
        $this->assertEquals(
            '/xh/?pagemanager&admin=plugin_config',
            $url->without('action')
        );
    }

    public function testWithoutReturnsNewInstance()
    {
        $url = $this->pagemanagerConfigUrl();
        $this->assertNotSame($url, $url->without('action'));
    }

    private function pagemanagerConfigUrl()
    {
        return new Url('/xh/', [
            'pagemanager' => null, 'admin' => 'plugin_config', 'action' => 'plugin_save'
        ]);
    }
}

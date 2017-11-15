<?php

/*
 * Copyright 2017 Christoph M. Becker
 *
 * This file is part of Pfw_XH.
 *
 * Pfw_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Pfw_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Pfw_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Pfw;

use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
    /**
     * @return void
     */
    public function testPathOnly()
    {
        global $sn, $su;

        $sn = '/';
        $su = '';
        $_GET = [];
        $this->assertEquals('/', Url::getCurrent()->getRelative());
    }

    /**
     * @return void
     */
    public function testPathAndPageOnly()
    {
        global $sn, $su;

        $sn = '/';
        $su = 'pagemanager';
        $_GET = ['pagemanager' => ''];
        $this->assertEquals('/?pagemanager', Url::getCurrent()->getRelative());
    }

    /**
     * @return void
     */
    public function testPathAndParamsOnly()
    {
        global $sn, $su;

        $sn = '/';
        $su = '';
        $_GET = ['foo' => 'bar'];
        $this->assertEquals('/?&foo=bar', Url::getCurrent()->getRelative());
    }

    /**
     * @return void
     */
    public function testFullUrl()
    {
        global $sn, $su;

        $sn = '/';
        $su = 'pagemanager';
        $_GET = ['pagemanager' => '', 'admin' => 'plugin_config', 'action' => 'plugin_edit', 'normal' => ''];
        $this->assertEquals(
            '/?pagemanager&admin=plugin_config&action=plugin_edit&normal',
            Url::getCurrent()->getRelative()
        );
    }

    /**
     * @return void
     */
    public function testToString()
    {
        global $sn, $su;

        $sn = '/';
        $su = 'foo';
        $_GET = ['foo' => '', 'bar' => 'baz'];
        $this->assertEquals('/?foo&bar=baz', (string) Url::getCurrent());
    }

    /**
     * @return void
     */
    public function testComplexPage()
    {
        global $sn, $su;

        $sn = '/';
        $su = 'S%C3%BCper/Lig';
        $_GET = ['S%C3%BCper/Lig' => ''];
        $this->assertEquals('/?S%C3%BCper/Lig', Url::getCurrent()->getRelative());
    }

    /**
     * @return void
     */
    public function testAbsoulte()
    {
        global $sn, $su;

        $sn = '/';
        $su = 'foo';
        $_GET = ['foo' => '', 'bar' => 'baz'];
        uopz_redefine('CMSIMPLE_URL', 'http://example.com/');
        $this->assertEquals('http://example.com/?foo&bar=baz', Url::getCurrent()->getAbsolute());
    }

    /**
     * @return void
     */
    public function testArrayParam()
    {
        global $sn, $su;

        $sn = '/';
        $su = '';
        $_GET = ['foo' => ['bar', 'baz']];
        $this->assertEquals('/?&foo%5B0%5D=bar&foo%5B1%5D=baz', Url::getCurrent()->getRelative());
    }

    /**
     * @return void
     */
    public function testWithAdds()
    {
        global $sn, $su;

        $sn = '/';
        $su = '';
        $_GET = ['foo' => 'bar'];
        $url = Url::getCurrent()->with('baz', 'qux');
        $this->assertEquals('/?&foo=bar&baz=qux', $url->getRelative());
    }

    /**
     * @return void
     */
    public function testWithReplaces()
    {
        global $sn, $su;

        $sn = '/';
        $su = '';
        $_GET = ['foo' => 'bar'];
        $url = Url::getCurrent()->with('foo', 'baz');
        $this->assertEquals('/?&foo=baz', $url->getRelative());
    }

    /**
     * @return void
     */
    public function testWithout()
    {
        global $sn, $su;

        $sn = '/';
        $su = '';
        $_GET = ['foo' => 'bar', 'baz' => 'qux'];
        $url = Url::getCurrent()->without('foo');
        $this->assertEquals('/?&baz=qux', $url->getRelative());
    }

    /**
     * @return void
     */
    public function testGetCurrentWithPage()
    {
        global $sn, $su;

        $sn = '/';
        $su = 'foo';
        $_GET = ['foo' => '', 'bar' => 'baz'];
        $url = Url::getCurrent();
        $this->assertEquals('/?foo&bar=baz', $url->getRelative());
    }

    /**
     * @return void
     */
    public function testGetCurrentWithoutPage()
    {
        global $sn, $su;

        $sn = '/';
        $su = '';
        $_GET = ['bar' => 'baz'];
        $url = Url::getCurrent();
        $this->assertEquals('/?&bar=baz', $url->getRelative());
    }
}

<?php

namespace Pfw;

class UrlTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
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

    private function defineConstant($name, $key)
    {
        if (defined($name)) {
            runkit_constant_redefine($name, $key);
        } else {
            define($name, $key);
        }
    }
}

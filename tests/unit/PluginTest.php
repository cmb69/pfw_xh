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

class PluginTest extends TestCase
{
    /**
     * @return void
     */
    public function testAlwaysRegistersPluginMenu()
    {
        uopz_redefine('XH_ADM', true);
        uopz_set_return('XH_registerStandardPluginMenuItems', function ($showMain) use (&$registered) {
            $registered = $showMain === false;
        }, true);
        uopz_set_return('XH_wantsPluginAdministration', false);
        (new Plugin)->run();
        $this->assertTrue($registered);
        uopz_unset_return('XH_registerStandardPluginMenuItems');
        uopz_unset_return('XH_wantsPluginAdministration');
    }

    /**
     * @return void
     */
    public function testPluginInfo()
    {
        global $admin;

        $admin = '';
        $this->setUpPluginAdministrationStubs();
        $infoControllerMock = $this->createMock(InfoController::class);
        $infoControllerMock->expects($this->once())->method('defaultAction');
        uopz_set_mock(InfoController::class, $infoControllerMock);
        (new Plugin)->run();
        $this->tearDownPluginAdministrationStubs();
    }

    /**
     * @return void
     */
    public function testLanguageAdministration()
    {
        global $admin, $action;

        $admin = 'plugin_language';
        $action = 'plugin_edit';
        $this->setUpPluginAdministrationStubs();
        uopz_set_return('plugin_admin_common', function () use (&$called) {
            $called = true;
        }, true);
        (new Plugin)->run();
        $this->assertTrue($called);
        $this->tearDownPluginAdministrationStubs();
        uopz_unset_return('plugin_admin_common');
    }

    /**
     * @return void
     */
    private function setUpPluginAdministrationStubs()
    {
        uopz_redefine('XH_ADM', true);
        uopz_set_return('XH_registerStandardPluginMenuItems', null);
        uopz_set_return('XH_wantsPluginAdministration', true);
        uopz_set_return('print_plugin_admin', null);
    }

    /**
     * @return void
     */
    private function tearDownPluginAdministrationStubs()
    {
        uopz_unset_return('XH_registerStandardPluginMenuItems');
        uopz_unset_return('XH_wantsPluginAdministration');
        uopz_unset_return('print_plugin_admin');
    }
}

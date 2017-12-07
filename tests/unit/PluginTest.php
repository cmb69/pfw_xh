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

use Pfw\TestCase;

class PluginTest extends TestCase
{
    /**
     * @var object
     */
    private $rspmimock;

    /**
     * @var object
     */
    private $wpamock;

    /**
     * @var object
     */
    private $ppamock;

    /**
     * @return void
     */
    public function testAlwaysRegistersPluginMenu()
    {
        $this->setConstant('XH_ADM', true);
        $rspmimock = $this->mockFunction('XH_registerStandardPluginMenuItems');
        $rspmimock->expects($this->once());
        $wpamock = $this->mockFunction('XH_wantsPluginAdministration');
        $wpamock->expects($this->any())->willReturn(false);
        (new Plugin)->run();
        $rspmimock->restore();
        $wpamock->restore();
    }

    /**
     * @return void
     */
    public function testPluginInfo()
    {
        global $admin;

        $admin = '';
        $this->setUpPluginAdministrationStubs();
        $this->wpamock->expects($this->any())->willReturn(true);
        $infoControllerMock = $this->createMock(InfoController::class);
        $infoControllerMock->expects($this->once())->method('defaultAction');
        $iccmock = $this->mockStaticMethod(InfoController::class, 'create');
        $iccmock->expects($this->any())->willReturn($infoControllerMock);
        (new Plugin)->run();
        $iccmock->restore();
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
        $this->wpamock->expects($this->any())->willReturn(true);
        $pacmock = $this->mockFunction('plugin_admin_common');
        $pacmock->expects($this->once())->willReturn(null);
        (new Plugin)->run();
        $this->tearDownPluginAdministrationStubs();
        $pacmock->restore();
    }

    /**
     * @return void
     */
    private function setUpPluginAdministrationStubs()
    {
        $this->setConstant('XH_ADM', true);
        $this->rspmimock = $this->mockFunction('XH_registerStandardPluginMenuItems');
        $this->wpamock = $this->mockFunction('XH_wantsPluginAdministration');
        $this->ppamock = $this->mockFunction('print_plugin_admin');
    }

    /**
     * @return void
     */
    private function tearDownPluginAdministrationStubs()
    {
        $this->rspmimock->restore();
        $this->wpamock->restore();
        $this->ppamock->restore();
    }
}

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
use Pfw\View\View;

class InfoControllerTest extends TestCase
{
    /**
     * @return void
     */
    public function testDefaultAction()
    {
        global $pth;

        $pth = ['folder' => ['plugins' => './plugins/']];
        uopz_flags(SystemCheckService::class, null, 0); // un-final-ize class
        $systemCheckServiceMock = $this->createMock(SystemCheckService::class);
        $systemCheckServiceMock->expects($this->any())->method('minPhpVersion')->willReturn($systemCheckServiceMock);
        $systemCheckServiceMock->expects($this->any())->method('minXhVersion')->willReturn($systemCheckServiceMock);
        $systemCheckServiceMock->expects($this->any())->method('writable')->willReturn($systemCheckServiceMock);
        $systemCheckServiceMock->expects($this->once())->method('getChecks')->willReturn([]);
        uopz_set_mock(SystemCheckService::class, $systemCheckServiceMock);
        uopz_flags(View::class, null, 0); // un-final-ize class
        $viewMock = $this->createMock(View::class);
        $viewMock->expects($this->once())->method('template')->with('info')->willReturn($viewMock);
        $viewMock->expects($this->once())->method('data')->with([
            'logo' => './plugins/pfw/pfw.png',
            'version' => '@PFW_VERSION@',
            'checks' => []
        ])->willReturn($viewMock);
        $viewMock->expects($this->once())->method('render');
        uopz_set_mock(View::class, $viewMock);
        (new InfoController)->defaultAction();
        uopz_unset_mock(View::class);
        uopz_flags(View::class, null, 4); // finalize class again
        uopz_unset_mock(SystemCheckService::class);
        uopz_flags(SystemCheckService::class, null, 4); // finalize class again
    }
}

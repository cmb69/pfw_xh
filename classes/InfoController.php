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

use Pfw\View\View;

final class InfoController
{
    public function defaultAction()
    {
        global $pth;

        (new View('pfw'))
            ->template('info')
            ->data([
                'logo' => "{$pth['folder']['plugins']}pfw/pfw.png",
                'version' => Plugin::VERSION,
                'checks' => (new SystemCheckService)
                    ->minPhpVersion('5.4.0')
                    ->minXhVersion('1.6.3')
                    ->writable("{$pth['folder']['plugins']}pfw/css/")
                    ->writable("{$pth['folder']['plugins']}pfw/languages/")
                    ->getChecks()
            ])
            ->render();
    }
}

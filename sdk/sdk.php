<?php

/*
 * Copyright 2017 Christoph M. Becker
 *
 * This file is part of the Pfw_XH SDK.
 *
 * The Pfw_XH SDK is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * The Pfw_XH SDK is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with the Pfw_XH SDK.  If not, see <http://www.gnu.org/licenses/>.
 */

spl_autoload_register(function ($class) {
    include_once __DIR__ . "/classes/$class.php";
});

if ($argc < 3) {
    goto usage;
}
switch ($argv[1]) {
    case 'templint':
        if (!file_exists($argv[2])) {
            exit(1);
        }
        (new Parser($argv[2]))->parse();
        exit;
}

usage:
echo "Usage: php sdk.php templint <filename>\n";
exit(1);

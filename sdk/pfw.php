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

spl_autoload_register(function ($className) {
    include_once __DIR__ . "/$className.php";
});

$options = getopt('vhg:c');

if (isset($options['v'])) {
    echo <<<EOT
PFW_XH @PFW_VERSION@

Copyright 2016 Christoph M. Becker

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

EOT;
    exit;
}
if (isset($options['g'])) {
    $generator = new PluginGenerator($options['g'], isset($options['c']));
    $generator->plugin();
    exit;
}
echo <<<EOT
Usage: php $argv[0] [-h] [-v] [-g <plugin> [-c]]

  -h           Show this help
  -v           Show version and copyright
  -g <plugin>  Generate skeleton for plugin <plugin>
  -c           Display colored output

EOT;

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

if ($argc < 2) {
    die('plugin name required');
}

$pluginName = $argv[1];

$config = <<<EOT
<?php

\$plugin_cf['$pluginName'][''] = "";

EOT;

$language = <<<EOT
<?php

\$plugin_tx['$pluginName']['']="";

EOT;

$index = <<<EOT
<?php

Pfw\Plugin::register()
    ->copyright('/* TODO: enter your copyright */')
    ->version('/* TODO: enter the plugin version */')
    ->admin()
        ->route(array(
            '$pluginName&admin=plugin_config' => 'Pfw\\\\ConfigAdminController',
            '$pluginName&admin=plugin_language' => 'Pfw\\\\LanguageAdminController',
            '$pluginName&admin=plugin_stylesheet' => 'Pfw\\\\StylesheetAdminController',
            '$pluginName' => 'Pfw\\DefaultAdminController'
        ))
;

EOT;

mkdir("./$pluginName");
mkdir("./$pluginName/classes");
mkdir("./$pluginName/config");
file_put_contents("./$pluginName/config/config.php", $config);
mkdir("./$pluginName/css");
file_put_contents("./$pluginName/css/stylesheet.css", '');
mkdir("./$pluginName/help");
file_put_contents("./$pluginName/help/help.htm", '');
mkdir("./$pluginName/languages");
file_put_contents("./$pluginName/languages/en.php", $language);
mkdir("./$pluginName/views");
file_put_contents("./$pluginName/admin.php", '');
file_put_contents("./$pluginName/index.php", $index);
file_put_contents("./$pluginName/version.nfo", '');

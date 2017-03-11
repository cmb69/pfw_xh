<?php

/*
Copyright 2016-2017 Christoph M. Becker
 
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

class PluginGenerator
{
    private $name;
    
    private $colors;
    
    public function __construct($name, $colors)
    {
        $this->name = $name;
        $this->colors = $colors;
    }
    
    public function plugin()
    {
        $this->cyan("Generating skeleton for plugin '{$this->name}'\n");
        $this->mkdir("./{$this->name}");
        $this->mkdir("./{$this->name}/classes");
        $this->mkdir("./{$this->name}/config");
        $this->mkfile("./{$this->name}/config/config.php", $this->config());
        $this->mkdir("./{$this->name}/css");
        $this->mkfile("./{$this->name}/css/stylesheet.css", '');
        $this->mkdir("./{$this->name}/help");
        $this->mkfile("./{$this->name}/help/help.htm", '');
        $this->mkdir("./{$this->name}/languages");
        $this->mkfile("./{$this->name}/languages/en.php", $this->language());
        $this->mkdir("./{$this->name}/views");
        $this->mkfile("./{$this->name}/admin.php", '');
        $this->mkfile("./{$this->name}/index.php", $this->index());
        $this->mkfile("./{$this->name}/version.nfo", '');
        $this->green("Skeleton generated\n");
    }

    private function config()
    {
        return <<<EOT
<?php

\$plugin_cf['{$this->name}'][''] = "";

EOT;
    }

    private function language()
    { 
        return <<<EOT
<?php

\$plugin_tx['{$this->name}']['alt_logo']="FIXME";

EOT;
    }
    
    private function index()
    {
        return <<<EOT
<?php

Pfw\System::registerPlugin('{$this->name}')
    ->copyright('FIXME')
    ->version('FIXME')
    ->admin()
        ->route(array(
            '{$this->name}&admin=plugin_config' => 'Pfw\\\\ConfigAdminController',
            '{$this->name}&admin=plugin_language' => 'Pfw\\\\LanguageAdminController',
            '{$this->name}&admin=plugin_stylesheet' => 'Pfw\\\\StylesheetAdminController',
            '{$this->name}' => 'Pfw\\PluginInfoController'
        ))
;

EOT;
    }
    
    private function mkdir($pathname)
    {
        echo "Creating directory $pathname => ";
        if (!file_exists($pathname) && mkdir($pathname)) {
            $this->green("done\n");
        } else {
            $this->red("failed\n");
            exit(1);
        }
    }
    
    private function mkfile($pathname, $contents)
    {
        echo "Creating file $pathname => ";
        if (!file_exists($pathname) && file_put_contents($pathname, $contents) !== false) {
            $this->green("done\n");
        } else {
            $this->red("failed\n");
            exit(1);
        }
    }
    
    private function cyan($string)
    {
        $this->color("\x1b[36;1m", $string);
    }
    
    private function green($string)
    {
        $this->color("\x1b[32;1m", $string);
    }
    
    private function red($string)
    {
        $this->color("\x1b[31;1m", $string);
    }
    
    private function color($color, $string)
    {
        if ($this->colors) {
            echo $color, $string, "\x1b[0m";
        } else {
            echo $string;
        }
    }
}

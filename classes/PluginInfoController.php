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

namespace Pfw;

/**
 * %Plugin info controllers
 *
 * The plugin info controller shows information about a plugin,
 * namely version, copyright, system check and user functions.
 *
 * This controller can be extended by other plugins, if so desired.
 */
class PluginInfoController extends Controller
{
    /**
     * Displays information about the plugin.
     *
     * @return void
     */
    public function indexAction()
    {
        $title = ucfirst($this->plugin->name());
        $this->response->setTitle($title);
        $view = $this->htmlView('info');
        $view->model = $this->plugin;
        $view->title = $title;
        $view->logo = $this->plugin->folder() . $this->plugin->name() . '.png';
        $view->checks = array_map(function ($check) {
            return (object) array(
                'text' => $check->text(),
                'statusIcon' => $this->plugin->folder() . 'images/' . $check->status() . '.png',
                'statusAlt' => 'syscheck_alt_' . $check->status()
            );
        }, $this->systemCheck()->checks());
        $view->userFuncs = array_map(function ($funcName) {
            return (object) ['name' => $funcName, 'signature' => $this->userFuncSignature($funcName)];
        }, $this->plugin->getFuncNames());
        $view->render();
    }
    
    /**
     * Returns the system check, ready to be rendered
     *
     * Supposed to be overriden in other plugins, if these have more demanding
     * or further requirements than the plugin framework itself.
     *
     * @return SystemChecks\SystemCheck
     */
    protected function systemCheck()
    {
        return (new SystemChecks\SystemCheck)
            ->mandatory()
                ->phpVersion('5.4.0')
                ->extension('XMLWriter')
                ->xhVersion('1.6');
    }

    /**
     * @param string $functionName
     * @return string
     */
    private function userFuncSignature($functionName)
    {
        $params = [];
        foreach ($this->plugin->funcParams($functionName) as $param) {
            $params[] = $param->getName();
        }
        return sprintf('%s(%s)', $functionName, implode(', ', $params));
    }
}

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
        $view->systemCheck = $this->systemCheck();
        $plugin = $this->plugin;
        $view->statusIcon = function ($check) use ($plugin) {
            return System::plugin('pfw')->folder() . 'images/' . $check->status() . '.png';
        };
        $view->statusAlt = function ($check) {
            return 'syscheck_alt_' . $check->status();
        };
        $view->userFuncSignature = $this->userFuncSignatureFunc();
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
        $systemCheck = new SystemChecks\SystemCheck();
        return $systemCheck
            ->mandatory()
                ->phpVersion('5.3')
                ->extension('SimpleXML')
                ->noMagicQuotes()
                ->xhVersion('1.6');
    }
    
    private function userFuncSignatureFunc()
    {
        $plugin = $this->plugin;
        return function ($functionName) use ($plugin) {
            $params = array();
            foreach ($plugin->funcParams($functionName) as $param) {
                $params[] = $param->getName();
            }
            return sprintf('%s(%s)', $functionName, implode(', ', $params));
        };
    }
}

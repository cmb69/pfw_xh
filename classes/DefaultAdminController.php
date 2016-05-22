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
 * Default admin controllers
 *
 * The default admin controller handles the plugin administration
 * when there is no `admin` parameter given. This is typically the case
 * when the webmaster selects the plugin in the plugin menu directly
 * (i.e. not any of its submenu entries).
 *
 * This controller is supposed to be extended in other plugins,
 * if so desired.
 */
class DefaultAdminController extends Controller
{
    /**
     * Handles the default action
     *
     * Displays some information about the plugin.
     *
     * @return void
     */
    public function indexAction()
    {
        $title = ucfirst($this->plugin->name());
        $this->response->setTitle($title);
        $view = $this->htmlView('info');
        $view->plugin = $this->plugin;
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
     * @return SystemCheck
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

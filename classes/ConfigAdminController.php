<?php

/*
 * Copyright 2016-2017 Christoph M. Becker
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

/**
 * Configuration admin controllers
 *
 * Adapters for the customary plugin loader facility
 * to let the webmaster edit the plugin stylesheet in the back-end.
 */
class ConfigAdminController extends Controller
{
    /**
     * Returns an appropriate file edit object
     *
     * @return XH_PluginConfigFileEdit
     */
    protected function createFileEdit()
    {
        global $pth;

        include_once "{$pth['folder']['classes']}FileEdit.php";
        return new \XH_PluginConfigFileEdit();
    }
    
    /**
     * Returns the dispatcher
     */
    public function getDispatcher()
    {
        return 'action';
    }

    /**
     * Displays the config edit form.
     *
     * @return void
     */
    public function indexAction()
    {
        $url = $this->getUrl('plugin_save');
        echo preg_replace(
            '/<form([^>]+)action="([^"]*)"/',
            "<form$1action=\"$url\"",
            $this->createFileEdit()->form()
        );
    }

// @codingStandardsIgnoreStart
    /**
     * Alias of ConfigAdminController::indexAction().
     *
     * @return void
     */
    public function plugin_editAction()
    {
// @codingStandardsIgnoreEnd
        $this->indexAction();
    }
    
// @codingStandardsIgnoreStart
    /**
     * Saves the configuration.
     *
     * @return void
     */
    public function plugin_saveAction()
    {
// @codingStandardsIgnoreEnd
        echo $this->createFileEdit()->submit();
    }
}

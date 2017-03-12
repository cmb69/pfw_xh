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

namespace Pfw\SystemChecks;

/**
 * Checks whether a certain CMSimple_XH plugin is active
 *
 * Note that this check might be too late, e.g. because a required
 * plugin is not available.
 * Therefore it is recommended to also explicitly document
 * this requirement prominently.
 */
class PluginCheck extends Check
{
    /**
     * The name of the plugin
     *
     * @var string
     */
    private $pluginName;

    /**
     * Constructs an instance
     *
     * @param bool   $isMandatory
     * @param string $pluginName
     */
    public function __construct($isMandatory, $pluginName)
    {
        parent::__construct($isMandatory);
        $this->pluginName = $pluginName;
    }

    /**
     * Returns whether the check succeeded, i.e.\ the requirement is fulfilled
     *
     * @return bool
     */
    protected function check()
    {
        return in_array($this->pluginName, XH_plugins());
    }

    /**
     * Returns the textual representation of the requirement
     *
     * @return string
     */
    public function getText()
    {
        return $this->lang->singular('syscheck_plugin', $this->pluginName);
    }
}

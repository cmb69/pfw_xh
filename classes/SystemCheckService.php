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

/**
 * Reusable component to provide plugin system checks.
 *
 * Example:
 *
 *     $checks = (new SystemCheckService)
 *         ->minPhpVersion('5.4.0')
 *         ->minXhVersion('1.6.3)
 *         ->getChecks();
 *
 */
class SystemCheckService
{
    /**
     * @var array
     */
    private $lang;

    /**
     * @var SystemCheck[]
     */
    private $checks = [];

    public function __construct()
    {
        global $plugin_tx;

        $this->lang = $plugin_tx['pfw'];
    }

    /**
     * @return SystemCheck[]
     */
    public function getChecks()
    {
        return $this->checks;
    }

    /**
     * @param string $version
     * @return $this
     */
    public function minPhpVersion($version)
    {
        $state = version_compare(PHP_VERSION, $version, 'ge')
            ? SystemCheck::SUCCESS
            : SystemCheck::FAILURE;
        $label = sprintf($this->lang['syscheck_phpversion'], $version);
        $this->checks[] = new SystemCheck($label, $state);
        return $this;
    }

    /**
     * @param string $extension
     * @param bool $isMandatory
     * @return $this
     */
    public function extension($extension, $isMandatory = true)
    {
        $state = extension_loaded($extension)
            ? SystemCheck::SUCCESS
            : ($isMandatory ? SystemCheck::FAILURE : SystemCheck::WARNING);
        $label = sprintf($this->lang['syscheck_extension'], $extension);
        $this->checks[] = new SystemCheck($label, $state);
        return $this;
    }

    /**
     * @param string $version
     * @return $this
     */
    public function minXhVersion($version)
    {
        $state = version_compare(CMSIMPLE_XH_VERSION, "CMSimple_XH $version", 'ge')
            ? SystemCheck::SUCCESS
            : SystemCheck::FAILURE;
        $label = sprintf($this->lang['syscheck_xhversion'], $version);
        $this->checks[] = new SystemCheck($label, $state);
        return $this;
    }

    /**
     * @param string $version
     * @return $this
     */
    public function minPfwVersion($version)
    {
        $state = defined('\Pfw\Plugin::VERSION') && version_compare(Plugin::VERSION, $version, 'ge')
            ? SystemCheck::SUCCESS
            : SystemCheck::FAILURE;
        $label = sprintf($this->lang['syscheck_pfwversion'], $version);
        $this->checks[] = new SystemCheck($label, $state);
        return $this;
    }

    /**
     * @param string $plugin
     * @return $this
     */
    public function plugin($plugin)
    {
        global $pth;

        $state = in_array($plugin, XH_plugins())
            ? SystemCheck::SUCCESS
            : SystemCheck::FAILURE;
        $label = sprintf($this->lang['syscheck_plugin'], $plugin);
        $this->checks[] = new SystemCheck($label, $state);
        return $this;
    }

    /**
     * @param string $folder
     * @return $this
     */
    public function writable($folder)
    {
        $state = is_writable($folder) ? SystemCheck::SUCCESS : SystemCheck::WARNING;
        $label = sprintf($this->lang['syscheck_writable'], $folder);
        $this->checks[] = new SystemCheck($label, $state);
        return $this;
    }
}

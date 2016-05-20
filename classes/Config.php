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
 * Access to the configuration of the plugins
 *
 * For instance, to check whether the `autoload` option
 * of jQuery4CMSimple is enabled, do:
 *
 *      $config = Config::instance('jquery');
 *      if ($config->get('autoload')) {
 *          ...
 *      }
 */
class Config
{
    /**
     * The plugin.
     *
     * @var string
     */
    private $plugin;

    /**
     * Constructs an instance.
     *
     * @param string $plugin
     */
    public function __construct($plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Returns the value of a certain $key.
     *
     * @param  string $key
     * @return mixed
     * @internal
     */
    public function get($key)
    {
        global $plugin_cf;

        if (isset($plugin_cf[$this->plugin][$key])) {
            return $plugin_cf[$this->plugin][$key];
        } elseif (isset($plugin_cf['pfw'][$key])) {
            return $plugin_cf['pfw'][$key];
        } else {
            return null;
        }
    }
}

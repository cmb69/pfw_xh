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
 * Access to the i18n of the plugins
 */
class Lang
{
    /**
     * The plugin.
     *
     * @var string
     */
    private $plugin;

    /**
     * Constructs an instance
     *
     * @param string $plugin
     */
    public function __construct($plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Returns the value of a certain offset
     *
     * @param  string $key
     * @return mixed
     * @internal
     */
    public function get($key)
    {
        global $plugin_tx;

        if (isset($plugin_tx[$this->plugin][$key])) {
            return $plugin_tx[$this->plugin][$key];
        } elseif (isset($plugin_tx['pfw'][$key])) {
            return $plugin_tx['pfw'][$key];
        } else {
            return null;
        }
    }
    /**
     * Returns a language text
     *
     * printf-style placeholders are replaced by additional parameters.
     *
     * @param  string $key
     * @param  array  ...$args
     * @return string
     */
    public function singular($key)
    {
        $args = array_slice(func_get_args(), 1);
        return vsprintf($this->get($key), $args);
    }

    /**
     * Returns a pluralized language text
     *
     * printf-style placeholders are replaced by additional parameters.
     *
     * @param  string $key
     * @param  int    $count
     * @param  array  ...$args
     * @return string
     */
    public function plural($key, $count)
    {
        if ($count == 1) {
            $suffix = '_singular';
        } elseif ($count > 2 && $count < 5) {
            $suffix = '_paucal';
            if ($this->get("$key$suffix") === null) {
                $suffix = '_plural';
            }
        } else {
            $suffix = '_plural';
        }
        $args = array_slice(func_get_args(), 1);
        return vsprintf($this->get("$key$suffix"), $args);
    }
}

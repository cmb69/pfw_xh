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

abstract class View
{
    /**
     * @var string
     */
    private $pluginname;

    /**
     * @var string
     */
    private $template;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @param string $pluginname
     */
    public function __construct($pluginname)
    {
        $this->pluginname = $pluginname;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function template($name)
    {
        global $pth;

        $this->template = "{$pth['folder']['plugins']}{$this->pluginname}/views/$name.php";
        return $this;
    }

    /**
     * @return $this
     */
    public function data(array $data)
    {
        $this->data = [];
        foreach ($data as $key => $value) {
            $this->data[$key] = ViewValue::create($this, $value);
        }
        return $this;
    }

    /**
     * @return void
     */
    public function render()
    {
        extract($this->data);
        include $this->template;
    }

    /**
     * @param mixed $value
     * @return string
     */
    abstract public function escape($value);

    /**
     * Return a properly escaped localized language text
     *
     * The `$key` is looked up in the active language file of the plugin the
     * view is associated with, and if it is not there it is looked up in
     * Pfw_XH's language file.  Additional arguments may be passed to substitute
     * printf-style placeholders in the language text.
     *
     * @param string $key
     * @return string
     */
    protected function text($key)
    {
        global $plugin_tx;

        if (isset($plugin_tx[$this->pluginname][$key])) {
            $string = $plugin_tx[$this->pluginname][$key];
        } else {
            $string = $plugin_tx['pfw'][$key];
        }
        return vsprintf($this->escape($string), array_slice(func_get_args(), 1));
    }
}

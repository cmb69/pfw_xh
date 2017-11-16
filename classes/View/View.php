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

namespace Pfw\View;

/**
 * Generation of properly escaped HTML.
 *
 * Example:
 *
 *     (new View($pluginname))
 *         ->template('foo')        // assign a template
 *         ->data(['bar' => 'baz']) // assign view data
 *         ->render();              // render the view
 */
final class View
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
     * Initialize the instance.
     *
     * `$pluginname` is the name of the instantiating plugin, and is used to
     * look up the `#template` and any language texts.
     *
     * @param string $pluginname
     */
    public function __construct($pluginname)
    {
        $this->pluginname = $pluginname;
    }

    /**
     * Assign a view template.
     *
     * The view template is looked up in the `views/` folder of the calling 
     * plugin with the basename `$name.php`.
     *
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
     * Assign view data.
     *
     * `$data` is an associative array, and its elements are available as local
     * variables in the view template.  All values, except booleans, are
     * encapsulated as `ViewValue`s to ensure proper HTML escaping which is
     * supposed to happen automagically.
     *
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
     * Render the view template.
     *
     * This method includes the assigned `#template` making the view `#data`
     * available as local variables.
     *
     * @return void
     */
    public function render()
    {
        extract($this->data);
        include $this->template;
    }

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
        return vsprintf(
            $this->escape($this->getLanguageString($key)),
            array_slice(func_get_args(), 1)
        );
    }

    /**
     * Return a properly escaped pluralized localized language text
     *
     * The `$key` is suffixed with the appropriate number suffix according to
     * the current language's rules, which works basically the same as gettext's
     * plurals.  The suffixed key is then looked up in the active language file
     * of the plugin the view is associated with, and if it is not there it is
     * looked up in Pfw_XH's language file.  Additional arguments may be passed
     * to substitute printf-style placeholders in the language text.
     *
     * @param string $key
     * @param int $count
     * @return string
     */
    protected function plural($key, $count)
    {
        $string = $this->getLanguageString("{$key}#{$this->getPluralSuffix($count)}");
        return vsprintf($this->escape($string), array_slice(func_get_args(), 1));
    }

    /**
     * @param string $key
     * @return string
     */
    private function getLanguageString($key)
    {
        global $plugin_tx;

        if (isset($plugin_tx[$this->pluginname][$key])) {
            return $plugin_tx[$this->pluginname][$key];
        } else {
            return $plugin_tx['pfw'][$key];
        }
    }

    /**
     * @param int $count
     * @return int
     */
    private function getPluralSuffix($count)
    {
        global $plugin_tx;

        $n = (string) $count;
        (string) $n; // silence PHPMD
        return eval("return (int) ({$plugin_tx['pfw']['plural_suffix']});");
    }

    /**
     * Escapes HTML.
     *
     * This method is supposed to be called automagically when needed, but never
     * explicitly.
     *
     * @param mixed $value
     */
    public function escape($value)
    {
        if ($value instanceof HtmlString) {
            return (string) $value;
        } else {
            return htmlspecialchars((string) $value, ENT_COMPAT | ENT_SUBSTITUTE, 'UTF-8');
        }
    }
}

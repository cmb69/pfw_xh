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
 * Views
 *
 * Views are helper objects that render a given PHP template, i.e. `echo` it.
 * The template usually contains PHP tags, which preferably are constrained
 * to simple loops and `echo` statements. To keep logic out of the templates,
 * the controller sets arbitrary view properties, which are available in the
 * template as local variables. These properties/variables are not restricted
 * to pure data, but may actually be callables, what supports the notion of
 * views as helper objects, opposed to having to inherit from View for
 * individual views.
 *
 * While the template has access to all private class members, this is
 * discouraged. Instead only the protected methods and the local variables
 * should be used.
 *
 * The output of all `echo` statements is supposed to be properly escaped.
 * A simple convention would be to explicitly escape all local variables in the
 * template. This implies that local variables never contain HTML strings,
 * what appears to be a problem in some cases. We might need an HtmlString
 * class, which could be treated specially in `escape`.
 *
 * Anyhow, complex output such as the system check or forms are probably
 * best passed to the view before they're rendered, and `render` is called
 * in the template. Language strings are automatically escaped, anyway.
 */
class View
{
    /**
     * The controller
     *
     * @var Controller
     */
    private $controller;

    /**
     * The template name, i.e. the basename of the file without extension
     *
     * @var string
     */
    private $template;

    /**
     * The plugin
     *
     * @var Plugin
     */
    private $plugin;

    /**
     * The language
     *
     * @var Lang
     */
    private $lang;

    /**
     * The store for the supplied properties
     *
     * This are available in the template as local variables.
     *
     * @var array
     *
     * @see __set()
     */
    private $data;

    /**
     * Constructs an instance
     *
     * @param Controller $controller
     * @param string     $template
     */
    public function __construct(Controller $controller, $template)
    {
        $this->controller = $controller;
        $this->template = $template;
        $this->plugin = $controller->plugin();
        $this->lang = $this->plugin->lang;
        $this->data = array();
    }

    /**
     * Supports setting view properties by clients
     *
     * This is some PHP magic which allows clients to treat as if they
     * were subclasses particularly created for the given template.
     *
     * All properties are available in the template as local variables.
     *
     * @param string $name
     * @param string $value
     *
     * @return void
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * Returns an escaped language string
     *
     * Additional paramters are processed in an sprintf style.
     *
     * @param string $key
     * @param array  ...$args
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function text($key)
    {
        return $this->escape(
            call_user_func_array(array($this->lang, 'singular'), func_get_args())
        );
    }

    /**
     * Returns an escaped pluralized language string
     *
     * Additional paramters are processed in an sprintf style.
     *
     * @param string $key
     * @param int    $count
     * @param array  ...$args
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function plural($key, $count)
    {
        return $this->escape(
            call_user_func_array(array($this->lang, 'plural'), func_get_args())
        );
    }

    /**
     * Renders the template.
     *
     * @return void
     */
    public function render()
    {
        extract($this->data);
        include $this->templatePath();
    }

    /**
     * Returns the path of the template file
     *
     * If the template is not found in the current plugin view folder,
     * we fall back to the view folder of the plugin framework.
     *
     * @return string
     */
    private function templatePath()
    {
        $filename = $this->plugin->folder . "views/{$this->template}.php";
        if (file_exists($filename)) {
            return $filename;
        }
        return $this->plugin->folder . "../pfw/views/{$this->template}.php";
    }

    /**
     * Returns a properly escaped string.
     *
     * This base implementation simply returns the string as is.
     *
     * @param string $string
     *
     * @return string
     */
    protected function escape($string)
    {
        return $string;
    }
}

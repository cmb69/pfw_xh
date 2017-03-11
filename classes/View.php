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
 * Views
 *
 * Views are helper objects that render a given PHP template, i.e. `echo` it.
 * The template usually contains PHP tags, which preferably are constrained
 * to simple loops and `echo` statements. To keep logic out of the templates,
 * the controller sets arbitrary view properties, which can have values of any
 * type; notably, callables are supported.
 *
 * These properties are available in the template as local variables,
 * and additionally as properties and methods, respectively, in which case they
 * yield properly escaped strings according to the view class.
 * Note, that all real view methods also return escaped strings.
 *
 * While the template has access to all private class members, this is
 * discouraged. Instead only the protected and public members and the local
 * variables should be used.
 *
 * To avoid XSS and garbled output (such as unescaped < in HTML) everything
 * that's `echo`'d from the template has to be properly escaped.
 * A simple convention supports this requirement: only `echo` view properties
 * and the results of calling view methods (as both are already escaped), i.e.
 * after each `echo` there should be a `$this->`.
 * To avoid escaping of strings containing HTML, use HtmlString.
 * Use the local variables when you don't `echo` (e.g. to iterate over them
 * with a foreach loop), or explicitly pass them to View::escape().
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
        $this->lang = $this->plugin->lang();
        $this->data = [];
    }
    
    /**
     * Allows to set data and callbacks as properties of the view.
     *
     * These properties are available in the template as local variables,
     * as well as properties of the view.  Accessing as view properties
     * automagically escapes the (return) values.
     *
     * As __set() will be triggered from outside the view, any valid PHP
     * identifier would be accepted as $name.  However, the template may
     * try to access these properties, but that is not possible if a real
     * property/method with this $name is defined.  Therefore we don't allow
     * to set $names which couldn't be retrieved later, and throw a warning
     * in this case.
     *
     * @param string $name
     * @param string $value
     * @return void
     */
    public function __set($name, $value)
    {
        if (is_callable($value)) {
            $forbidden = method_exists($this, $name);
        } else {
            $forbidden = property_exists($this, $name);
        }
        if ($forbidden) {
            trigger_error("property $name not allowed", E_USER_WARNING);
            return;
        }
        $this->data[$name] = $value;
    }

    /**
     * Allows to retrieve previously set data as view property.
     *
     * The data will be escaped.
     *
     * @param string $name
     *
     * @return string
     */
    public function __get($name)
    {
        return $this->escape($this->data[$name]);
    }

    /**
     * Allows to call previously set callbacks as view methods.
     *
     * The return value will be escaped.
     *
     * @param string $name
     * @param array  ...$args
     *
     * @return string
     */
    public function __call($name, $args)
    {
        return $this->escape(call_user_func_array($this->data[$name], $args));
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
            call_user_func_array([$this->lang, 'singular'], func_get_args())
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
            call_user_func_array([$this->lang, 'plural'], func_get_args())
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
     * Returns the rendered template.
     *
     * @return string
     * @since 0.2.0
     */
    public function __toString()
    {
        ob_start();
        $this->render();
        return ob_get_clean();
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
        $filename = $this->plugin->folder() . "views/{$this->template}.php";
        if (file_exists($filename)) {
            return $filename;
        }
        return $this->plugin->folder() . "../pfw/views/{$this->template}.php";
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
    
    /**
     * Returns the properly escaped URL of the given action of the current
     * controller.
     *
     * Actually, this is just a convenience wrapper for Controller::url().
     * More complex cases (such as setting additional query
     * parameters) would require to pass a respective function to the view
     * from the controller.
     *
     * @param string $action
     * @return string
     *
     * @todo Consider having a HtmlUrl which subclasses Url and overrides
     *       __toString().
     */
    protected function url($action)
    {
        return $this->escape($this->controller->url($action));
    }
}

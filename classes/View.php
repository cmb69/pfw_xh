<?php

namespace Pfw;

/**
 * Views
 *
 * Views are helper objects that render a given PHP template, i.e. `echo` it.
 * The template usually contains PHP tags, which preferably are constrained
 * to simple loops and `echo` statements. The output of all `echo` statements
 * is supposed to be properly escaped.
 *
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
    private $controller;

    private $template;

    private $plugin;

    private $lang;

    private $data;

    public function __construct(Controller $controller, $template)
    {
        $this->controller = $controller;
        $this->template = $template;
        $this->plugin = $controller->plugin();
        $this->lang = $this->plugin->lang();
        $this->data = array();
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    protected function text($key)
    {
        $lang = $this->plugin->lang();
        return $this->escape(
            call_user_func_array(array($lang, 'singular'), func_get_args())
        );
    }

    protected function plural($key)
    {
        $lang = $this->plugin->lang();
        return $this->escape(
            call_user_func_array(array($lang, 'plural'), func_get_args())
        );
    }

    public function render()
    {
        extract($this->data);
        include $this->templatePath();
    }

    private function templatePath()
    {
        $filename = $this->plugin->folder() . "views/{$this->template}.php";
        if (file_exists($filename)) {
            return $filename;
        }
        return $this->plugin->folder() . "../pfw/views/{$this->template}.php";
    }

    protected function escape($string)
    {
        return $string;
    }
}

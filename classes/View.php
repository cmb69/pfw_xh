<?php

namespace Pfw;

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

    public function render()
    {
        extract($this->data);
        include $this->templatePath();
    }

    private function templatePath()
    {
        return $this->plugin->folder() . "views/{$this->template}.php";
    }

    protected function escape($string)
    {
        return $string;
    }
}

<?php

namespace Pfw;

abstract class Controller
{
    protected $plugin;

    protected $request;

    protected $response;

    protected $config;

    protected $lang;

    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
        $this->request = Request::instance();
        $this->response = Response::instance();
        $this->config = $plugin->config();
        $this->lang = $plugin->lang();
    }

    public function plugin()
    {
        return $this->plugin;
    }

    public function contentFolder()
    {
        global $pth;

        return $pth['folder']['content'];
    }

    protected function view($template)
    {
        return new View($this, $template);
    }
}

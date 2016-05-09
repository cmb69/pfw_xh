<?php

/**
 * The plugin framework
 */
namespace Pfw;

abstract class FuncController extends Controller
{
    private $actionParam;

    public function __construct(Plugin $plugin, $actionParam)
    {
        parent::__construct($plugin);
        $this->actionParam = $actionParam;
    }

    public function url($action)
    {
        return $this->request->url()->with($this->actionParam, $action);
    }
}

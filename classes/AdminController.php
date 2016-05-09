<?php

/**
 * The plugin framework
 */
namespace Pfw;

abstract class AdminController extends Controller
{
    public function url($action)
    {
        return $this->request->url()->with('action', "plugin_$action");
    }
}

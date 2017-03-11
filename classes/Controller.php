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
 * Controllers
 *
 * All real functionality of plugins using the plugin framework is supposed
 * to be initiated in controllers, or more specificially in one of the
 * actions implemented by a controller. The routing, i.e. finding out
 * which action of which controller to call, is defined by the Plugin.
 */
abstract class Controller
{
    /**
     * The dispatcher without prefix
     *
     * Override this constant in your controllers to set the dispatcher.
     *
     * @see getDispatcher()
     */
    const DISPATCHER = null;

    /**
     * The plugin
     *
     * @var Plugin $plugin
     */
    protected $plugin;

    /**
     * The current request
     *
     * @var Request $request
     */
    protected $request;

    /**
     * The current response
     *
     * @var Response $response
     */
    protected $response;

    /**
     * The configuration
     *
     * @var Config $config
     */
    protected $config;

    /**
     * The language
     *
     * @var Lang $lang
     */
    protected $lang;

    /**
     * Constructs a controller
     *
     * Controllers are supposed to be created automatically by the plugin,
     * so don't create controllers on your own.
     *
     * @param Plugin $plugin
     */
    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
        $this->request = System::request();
        $this->response = System::response();
        $this->config = $plugin->config();
        $this->lang = $plugin->lang();
    }
    
    /**
     * Returns the dispatcher
     *
     * The dispatcher denotes the query parameter which is used to determine
     * the action to invoke. If the dispatcher is null, the index action
     * will be invoked.
     *
     * @return string
     */
    public function getDispatcher()
    {
        if (static::DISPATCHER === null) {
            return null;
        } else {
            return $this->plugin->name() . '_' . static::DISPATCHER;
        }
    }

    /**
     * Returns the plugin
     *
     * @return Plugin
     */
    public function plugin()
    {
        return $this->plugin;
    }

    /**
     * Returns the path of CMSimple_XH's content folder of the current language.
     *
     * @return string
     */
    public function contentFolder()
    {
        global $pth;

        return $pth['folder']['content'];
    }

    /**
     * Returns a configuration option
     *
     * @param string $key
     *
     * @return string
     */
    protected function config($key)
    {
        return $this->config->get($key);
    }

    /**
     * Creates a view associated to a certain template
     *
     * This is the preferred way to create a view; don't create views
     * directly (i.e. via `new %View`).
     *
     * @param string $template
     *
     * @return View
     */
    protected function view($template)
    {
        return new View($this, $template);
    }

    /**
     * Creates an HTML view associated to a certain template
     *
     * This is the preferred way to create a view; don't create views
     * directly (i.e. via `new %View`).
     *
     * @param string $template
     *
     * @return View
     */
    protected function htmlView($template)
    {
        return new HtmlView($this, $template);
    }

    /**
     * Creates a form builder
     *
     * This is the preferred way to create a form builder.
     *
     * @param string $action
     *
     * @return FormBuilder
     */
    protected function formBuilder($action)
    {
        return new Forms\FormBuilder($this->plugin->name(), $this->lang, $action);
    }

    /**
     * Triggers an immediate 303 See Other redirect
     *
     * Actually, of course only the respective header is sent to the user agent,
     * which is supposed to relocate to the new URL. In any way, the script is
     * terminated, and this function will not return.
     *
     * @param Url $url
     *
     * @return void
     */
    public function seeOther(Url $url)
    {
        $this->response->redirect($url->absolute(), 303);
    }

    /**
     * Returns an URL for the given action
     *
     * Actually, the current URL is modified so that it points to the given
     * action of this controller. All other query parameters are kept as they
     * are.
     *
     * @param string $action
     *
     * @return Url
     */
    public function url($action)
    {
        return $this->request->url()->with($this->getDispatcher(), $action);
    }
}

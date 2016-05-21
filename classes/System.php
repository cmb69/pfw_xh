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
 * The system.
 *
 * A singleton registry for global plugin framework state.
 * It is not meant to be used directly from plugins, except for
 * System::registerPlugin(), which has to be called once for each plugin.
 */
class System
{
    private static $instance;
    
    /**
     * @var Request
     */
    private $request;
    
    /**
     * @var Response
     */
    private $response;
    
    /**
     * @var Plugin[]
     */
    private $plugins = array();
    
    /**
     * @var Config[]
     */
    private $configs = array();
    
    /**
     * @var Lang[]
     */
    private $langs = array();
    
    /**
     * The protected methods are publicly available as static methods.
     */
    public static function __callStatic($name, $args)
    {
        switch ($name) {
            case 'request':
            case 'response':
            case 'plugin':
            case 'config':
            case 'lang':
            case 'registerPlugin':
            case 'runPlugins':
                return call_user_func_array(array(self::instance(), $name), $args);
        }
    }
    
    /**
     * This method is for testing purposes only, so that System can be faked.
     */
    public static function loadInstance(System $system)
    {
        self::$instance = $system;
    }
    
    private static function instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * @return Request
     */
    protected function request()
    {
        if (!isset($this->request)) {
            $this->request = new Request();
        }
        return $this->request;
    }
    
    /**
     * @return Response
     */
    protected function response()
    {
        if (!isset($this->response)) {
            $this->response = new Response();
        }
        return $this->response;
    }
    
    /**
     * Returns a registered plugin.
     *
     * @param string $name
     *
     * @return Plugin
     */
    protected function plugin($name)
    {
        return $this->plugins[$name];
    }
    
    /**
     * Returns a plugin configuration.
     *
     * @param  string $pluginName
     * @return Config
     */
    protected function config($pluginName)
    {
        if (!isset($this->configs[$pluginName])) {
            $this->configs[$pluginName] = new Config($pluginName);
        }
        return $this->configs[$pluginName];
    }
    
     /**
     * Returns a plugin language.
     *
     * @param  string $pluginName
     * @return Lang
     */
    protected function lang($pluginName)
    {
        if (!isset($this->langs[$pluginName])) {
            $this->langs[$pluginName] = new Lang($pluginName);
        }
        return $this->langs[$pluginName];
    }

     /**
     * Registers a new plugin and returns it.
     *
     * @return Plugin
     */
    protected function registerPlugin()
    {
        $plugin = new Plugin();
        $this->plugins[$plugin->name] = $plugin;
        return $plugin;
    }
    
    /**
     * Runs all plugins
     *
     * This method is automagically called by the plugin framework
     * after all plugins have been loaded.
     *
     * @return void
     */
    protected function runPlugins()
    {
        foreach ($this->plugins as $plugin) {
            $plugin->run();
        }
    }
}

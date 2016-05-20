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
 * This static class represents the global state of the plugin framework.
 * It is not meant to be used directly from plugins, except for
 * System::registerPlugin(), which has to be called once for each plugin.
 */
class System
{
    /**
     * @var Request
     */
    private static $request;
    
    /**
     * @var Response
     */
    private static $response;
    
    /**
     * @var Plugin[]
     */
    private static $plugins = array();
    
    /**
     * @var Config[]
     */
    private static $configs = array();
    
    /**
     * @var Lang[]
     */
    private static $langs = array();
    
    /**
     * @return Request
     */
    public static function request()
    {
        if (!isset(self::$request)) {
            self::$request = new Request();
        }
        return self::$request;
    }
    
    /**
     * @return Response
     */
    public static function response()
    {
        if (!isset(self::$response)) {
            self::$response = new Response();
        }
        return self::$response;
    }
    
    /**
     * Returns a registered plugin.
     *
     * @param string $name
     *
     * @return Plugin
     */
    public static function plugin($name)
    {
        return self::$plugins[$name];
    }
    
    /**
     * Returns a plugin configuration.
     *
     * @param  string $pluginName
     * @return Config
     */
    public static function config($pluginName)
    {
        global $plugin_cf;

        if (!isset($plugin_cf[$pluginName])) {
            return null; // TODO: or exception or simply allow that?
        }
        if (!isset(self::$configs[$pluginName])) {
            $configs[$pluginName] = new Config($pluginName);
        }
        return $configs[$pluginName];
    }
    
     /**
     * Returns a plugin language.
     *
     * @param  string $pluginName
     * @return Lang
     */
    public static function lang($pluginName)
    {
        global $plugin_tx;

        if (!isset($plugin_tx[$pluginName])) {
            return null; // or exception or simply allow that?
        }
        if (!isset(self::$langs[$pluginName])) {
            self::$langs[$pluginName] = new Lang($pluginName);
        }
        return self::$langs[$pluginName];
    }

     /**
     * Registers a new plugin and returns it.
     *
     * @return Plugin
     */
    public static function registerPlugin()
    {
        $plugin = new Plugin();
        self::$plugins[$plugin->name] = $plugin;
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
    public static function runPlugins()
    {
        foreach (self::$plugins as $plugin) {
            $plugin->run();
        }
    }
}

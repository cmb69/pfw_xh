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
 * PFW plugins
 *
 * Every plugin that uses the plugin framework is supposed to register
 * itself as plugin exactly once, what usually should be done in index.php.
 * After that some simple properties (copyright, version) are supposed to be
 * set, and the @ref Route "routing" to the @ref Controller "controllers" is set up.
 * All that is preferable done using the fluent interface. For example:
 *
 *      \Pfw\System::registerPlugin('my_plugin')
 *          ->copyright('2016 by me')
 *          ->version('1.0')
 *          // set up general routes
 *          ->route(array(
 *              // ...
 *          ))
 *          // set up routes for admin mode
 *          ->admin()
 *              ->route(array(
 *                  // ...
 *              ))
 *          // declare a user function
 *          ->func('my_plugin_do_it')
 *              // and set up routes for this function
 *              ->route(array(
 *                  // ...
 *              ))
 *      ;
 */
class Plugin
{
    /**
     * The plugin name
     *
     * @var string
     */
    private $name;

    /**
     * The plugin folder
     *
     * @var string
     */
    private $folder;

    /**
     * The plugin version
     *
     * @var string
     */
    private $version;
    
    /**
     * @var string
     */
    private $copyright;

    /**
     * The plugin configuration
     *
     * @var Config
     */
    private $config;

    /**
     * The plugin language
     *
     * @var Lang
     */
    private $lang;

    /**
     * The general routes
     *
     * @var Route[]
     */
    private $routes = array();

    /**
     * Whether we're inside an admin section
     *
     * @var bool
     */
    private $admin = false;

    /**
     * The admin routes
     *
     * @var Route[]
     */
    private $adminRoutes = array();

    /**
     * The map of user function names to their routes
     *
     * @var array
     */
    private $funcs = array();

    /**
     * The name of current user function
     *
     * @var string
     */
    private $currentFunc;

    /**
     * Constructs an instance
     *
     * @param string $name The basename of the plugin folder.
     */
    public function __construct($name)
    {
        global $pth;

        $this->name = $name;
        $this->folder = $pth['folder']['plugin'];
        $this->version = 'UNKNOWN';
        $this->config = System::config($name);
        $this->lang = System::lang($name);
    }
    
    /**
     * Returns the plugin name.
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }
    
    /**
     * Returns the plugin configuration.
     *
     * @return Config
     */
    public function config()
    {
        return $this->config;
    }
    
    /**
     * Returns the plugin language.
     *
     * @return Lang
     */
    public function lang()
    {
        return $this->lang;
    }
    
    /**
     * Returns the path of the plugin folder.
     *
     * @return string
     */
    public function folder()
    {
        return $this->folder;
    }

    /**
     * Gets or sets the copyright.
     *
     * @param string $copyright
     *
     * @return $this
     */
    public function copyright($copyright = null)
    {
        if (!isset($copyright)) {
            return $this->copyright;
        }
        $this->copyright = $copyright;
        return $this;
    }

    /**
     * Gets or sets the plugin version
     *
     * @param string $version
     *
     * @return $this
     */
    public function version($version = null)
    {
        if (!isset($version)) {
            return $this->version;
        }
        $this->version = $version;
        return $this;
    }

    /**
     * Returns the names of all registered user functions
     *
     * @return array<string>
     */
    public function getFuncNames()
    {
        return array_keys($this->funcs);
    }

    /**
     * Returns the registered routes of a user function
     *
     * @param string $name A user function name
     *
     * @return array<Route>
     */
    public function getFuncRoutes($name)
    {
        return $this->funcs[$name];
    }
    
    /**
     * Returns the parameters of a user function.
     *
     * @return array<ReflectionParameter>
     */
    public function funcParams($name)
    {
        $routes = $this->funcs[$name];
        if (empty($routes)) {
            return array();
        }
        $params = $routes[0]->controllerParams();
        array_shift($params);
        return $params;
    }

    /**
     * Registers a route
     *
     * Note that it's possible to register multiple routes even for the same
     * section. That is necessary, if multiple actions of different controllers
     * might have to be invoked for a single request.
     *
     * @param array $route A map of query patterns to controller names
     *
     * @return $this
     */
    public function route(array $route)
    {
        if ($this->currentFunc) {
            $this->funcs[$this->currentFunc][] = new Route($this, $route);
        } elseif ($this->admin) {
            $this->adminRoutes[] = new Route($this, $route);
        } else {
            $this->routes[] = new Route($this, $route);
        }
        return $this;
    }

    /**
     * Starts an admin section
     *
     * All following routes will be resolved only when we're in admin mode.
     *
     * @return $this
     */
    public function admin()
    {
        $this->currentFunc = null;
        $this->admin = true;
        return $this;
    }

    /**
     * Registers a user function
     *
     * Traditionally, user functions are just plain PHP functions.
     * This has the drawback that the system is not aware which user
     * functions are available, because it cannot distinguish between
     * internal helper functions.
     * The even greater drawback with regard to the plugin framework
     * is that a plain PHP user function would have to create the
     * appropriate controller object passing the appropriate plugin
     * as parameter, and to dynamically call the appropriate action.
     * All that is handled automagically by this method.
     * You still can write and use plain PHP functions as user functions,
     * though this is not recommended, except maybe for extremly simple cases.
     *
     * All following routes will be resolved only when the function is
     * actually called (for instance, from the template).
     *
     * @param string $name
     *
     * @return $this
     */
    public function func($name)
    {
        $this->admin = false;
        $this->currentFunc = $name;
        $this->funcs[$name] = array();
        return $this;
    }

    /**
     * Runs the plugin.
     *
     * @return void
     */
    public function run()
    {
        global $plugin, $o;

        $plugin = $this->name;
        pluginFiles($this->name);
        ob_start();
        foreach ($this->routes as $route) {
            $route->resolve();
        }
        $o .= ob_get_clean();
        if (User::isAdmin()) {
            $this->runAdmin();
        }
        foreach (array_keys($this->funcs) as $name) {
            $this->defineFunc($this->name, $name);
        }
    }
    
    private function runAdmin()
    {
        global $o;
        
        $this->buildAdminMenu();
        ob_start();
        foreach ($this->adminRoutes as $route) {
            $route->resolve();
        }
        $o .= ob_get_clean();
    }
    
    /**
     * @return void
     */
    private function buildAdminMenu()
    {
        global $pth;
        
        if ($this->isAdmin() && $this->config->get('show_menu')) {
            pluginMenu('ROW');
        }
        foreach ($this->adminRoutes as $route) {
            foreach ($route->adminMenuItems() as $key => $value) {
                $text = $this->lang->singular("menu_$key");
                $this->addMenuItem($text, $value);
            }
        }
        if ($pth['file']['plugin_help']) {
            $text = $this->lang->singular("menu_help");
            $this->addMenuItem($text, $pth['file']['plugin_help'], 'target="_blank"');
        }
        if ($this->isAdmin() && $this->config->get('show_menu')) {
            System::response()->append(pluginMenu('SHOW'));
        }
    }
    
    /**
     * Returns whether the plugin administration is requested.
     *
     * @warning This method doesn't check whether we're in admin mode, though.
     */
    private function isAdmin()
    {
        return isset($GLOBALS[$this->name]) && $GLOBALS[$this->name] == 'true';
    }
    
    private function addMenuItem($text, $url, $target = null)
    {
        XH_registerPluginMenuItem($this->name, $text, $url);
        if ($this->isAdmin() && $this->config->get('show_menu')) {
            pluginMenu('TAB', $url, $target, $text);
        }
    }

    /**
     * @todo For better error reporting of user function calls in debug mode,
     *       we might define the function with its proper parameters retrieved
     *       via Reflection of the controllers constructor.
     *       That might hurt performance, though, so we might want to make
     *       this optional via a config option.
     */
    private function defineFunc($plugin, $name)
    {
        eval(<<<EOS
function $name()
{
    \$plugin = Pfw\System::plugin('$plugin');
    ob_start();
    foreach (\$plugin->getFuncRoutes('$name') as \$route) {
        \$route->resolve(func_get_args());
    }
    return ob_get_clean();
}
EOS
        );
    }
}

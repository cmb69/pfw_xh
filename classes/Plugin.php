<?php

/**
 * PFW plugins
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw;

/**
 * PFW plugins
 *
 * Every plugin that uses the plugin framework is supposed to register
 * itself as plugin exactly once, what usually should be done in index.php.
 * After that some simple properties (copyright, version) are supposed to be
 * set, and the routing to the controllers is set up (admin, func, page).
 * All that is preferable done using the fluent interface. For example:
 *
 *      \Pfw\Plugin::register()
 *          ->copyright('2016 by me')
 *          ->version('1.0')
 *          // declare that the plugin has an administration area:
 *          ->admin()
 *          // declare that there is a user function
 *          // with the name of the plugin:
 *          ->func()
 *          // declare that the plugins dynamically
 *          //creates the page "dynamic":
 *          ->page('dynamic')
 *      ;
 *
 * The routing implies that there are respective controllers declared
 * following a particular naming scheme. If a route is acknowledged by the
 * plugin framework, it automagically creates the respective controller
 * and calls the respective action method passing the necessary constructor
 * and action method parameters. Routes are not exclusive, i.e. multiple
 * routes can be acknowledged for a single plugin within a single request.
 *
 * The naming scheme is documented for the individual controller types,
 * see {@see AdminController} and {@see FuncController}.
 *
 * @property-read Config   $config    The plugin configuration (read-only)
 * @property-read string   $copyright The copyright of the plugin (read-only)
 * @property-read string   $folder    The plugin folder (read-only)
 * @property-read string[] $functions The names of the registered user functions (read-only)
 * @property-read Lang     $lang      The plugin language (read-only)
 * @property-read string   $name      The plugin name (read-only)
 * @property-read string   $version   The plugin version (read-only)
 *
 * @todo Check whether it's possible to actually run the plugin after
 *       plugin loading (seems to be an issue with plugin_admin_common).
 *       This would imply that admin() and func() would actually register
 *       only, and that we would need a run() method for immediate tasks.
 */
class Plugin
{
    /**
     * The plugin instances
     *
     * @var Plugin[]
     */
    private static $instances = array();

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
     * Registers the plugin
     *
     * Actually, this is just an alias for `new Plugin()`,
     * but we prefer the more explicit naming (we actually want
     * the user to register a plugin instead of creating it)
     * and we work around the PHP 5.3 limitation regarding
     * class member access on instantiation.
     *
     * @return self
     */
    public static function register()
    {
        return new self();
    }

    /**
     * Returns a plugin instance
     *
     * @param string $name
     *
     * @return Plugin
     */
    public static function instance($name)
    {
        return self::$instances[$name];
    }

    /**
     * Runs all plugins
     *
     * This method is automagically called by the plugin framework
     * after all plugins have been loaded.
     *
     * @return void
     */
    public static function runAll()
    {
        foreach (self::$instances as $instance) {
            $instance->run();
        }
    }

    /**
     * Constructs an instance
     */
    private function __construct()
    {
        global $plugin, $pth;

        $this->name = $plugin;
        $this->folder = $pth['folder']['plugin'];
        $this->version = 'UNKNOWN';
        $this->config = Config::instance($plugin);
        $this->lang = Lang::instance($plugin);
        self::$instances[$plugin] = $this;
    }
    
    /**
     * Makes some properties available
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        switch ($name) {
            case 'config':
            case 'copyright':
            case 'folder':
            case 'lang':
            case 'name':
            case 'version':
                return $this->{$name};
            default:
                assert(false);
        }
    }

    /**
     * Sets the copyright
     *
     * @param string $copyright
     *
     * @return $this
     */
    public function copyright($copyright)
    {
        $this->copyright = $copyright;
        return $this;
    }

    /**
     * Sets the plugin version
     *
     * @param string $version
     *
     * @return $this
     */
    public function version($version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * Returns the names of all registered user functions
     *
     * @return string[]
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
     * @return Route[]
     */
    public function getFuncRoutes($name)
    {
        return $this->funcs[$name];
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
     * @return $this;
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
     * Runs the plugin
     *
     * @return void
     *
     * @todo How should we handle the plugin menu?
     * @todo For better error reporting of user function calls in debug mode,
     *       we might define the function with its proper parameters retrieved
     *       via Reflection of the controllers constructor.
     *       That might hurt performance, though, so we might want to make
     *       this optional via a config option.
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
        if (defined('XH_ADM') && XH_ADM) {
            XH_registerStandardPluginMenuItems(false);
            ob_start();
            foreach ($this->adminRoutes as $route) {
                $route->resolve();
            }
            $o .= ob_get_clean();
        }
        foreach (array_keys($this->funcs) as $name) {
            eval(<<<EOS
function $name()
{
    \$plugin = Pfw\Plugin::instance('$plugin');
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
}

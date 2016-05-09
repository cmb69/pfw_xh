<?php

/**
 * The plugin framework
 */
namespace Pfw;

/**
 * PFW plugins
 *
 * @todo Check whether it's possible to actually run the plugin after
 *       plugin loading (seems to be an issue with plugin_admin_common).
 *       This would imply that admin() and func() would actually register
 *       only, and that we would need a run() method for immediate tasks.
 */
class Plugin
{
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

    private $version;

    private $config;

    private $lang;

    /**
     * @var array<string>
     */
    private $functions = array();

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
    
    public static function instance($name)
    {
        return self::$instances[$name];
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
     * Returns the plugin name
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Returns the plugin folder
     *
     * @return string
     */
    public function folder()
    {
        return $this->folder;
    }
    
    public function config()
    {
        return $this->config;
    }

    public function lang()
    {
        return $this->lang;
    }

    public function copyright($copyright = null)
    {
        if (!isset($copyright)) {
            return $this->copyright;
        }
        $this->copyright = $copyright;
        return $this;
    }

    /**
     * Sets or returns the plugin version
     *
     * @return string
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
     * Returns all registered user function names
     *
     * @return array<string>
     */
    public function functions()
    {
        return $this->functions;
    }

    public function admin()
    {
        if (!defined('XH_ADM') || !XH_ADM) {
            return $this;
        }
        XH_registerStandardPluginMenuItems(false);
        if (!isset($GLOBALS[$this->name]) || $GLOBALS[$this->name] != 'true') {
            return $this;
        }
        $controller = ucfirst($this->name) . '\\' . $this->adminController();
        $action = $this->adminAction();
        if (class_exists($controller)) {
            $controller = new $controller($this);
            ob_start();
            $controller->{$action}();
            Response::instance()->append(ob_get_clean());
        }
        return $this;
    }

    private function adminController()
    {
        global $admin;

        initvar('admin');
        if (preg_match('/^plugin_(.*)$/', $admin, $matches)) {
            $name = ucfirst($matches[1]);
        } else {
            $name = 'Default';
        }
        return "{$name}AdminController";
    }

    private function adminAction()
    {
        global $action;
        
        if (preg_match('/^plugin_(.*)$/', $action, $matches)) {
            $name = ucfirst($matches[1]);
        } else {
            $name = 'Default';
        }
        return "handle$name";
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
     * as parameter, and to dynamically call the appropriate action
     * passing the user function's arguments.
     * All that is handled automagically by this method.
     * You still can write and use plain PHP functions as user functions,
     * though this is not recommended.
     *
     * If the name is ommitted, the function name is just the plugin name,
     * what is useful if there is only one user function or there is a
     * main user function. Otherwise the function name is prefixed with
     * the plugin name and an underscore. Example for a `foo` plugin:
     *
     *      func() // function foo() {}
     *      func('bar') // function foo_bar() {}
     *
     * @param string $name
     * @param string $actionParam
     *
     * @return self
     */
    public function func($name = null, $actionParam = null)
    {
        if (isset($name)) {
            $functionName = "{$this->name}_$name";
        } else {
            $functionName = $this->name;
            $name = 'default';
        }
        $this->functions[] = $functionName;
        $controller = ucfirst($this->name) . '\\Default' . ucfirst($name) . 'FuncController';
        eval(<<<EOS
function $functionName()
{
    \$controller = new $controller(Pfw\\Plugin::instance('{$this->name}'), '$actionParam');
    \$action = isset(\$_GET['$actionParam']) ? ucfirst(\$_GET['$actionParam']) : 'Default';
    \$action = "handle\$action";
    ob_start();
    call_user_func_array(array(\$controller, \$action), func_get_args());
    return ob_get_clean();
}
EOS
        );
        return $this;
    }
}

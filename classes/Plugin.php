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
     * The names of the registered functions
     *
     * @var string[]
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
            case 'functions':
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
     * Declares that the plugin has an administration interface
     *
     * @return void
     */
    public function admin()
    {
        if (!defined('XH_ADM') || !XH_ADM) {
            return $this;
        }
        $controllerNames = $this->getAdminControllerNames();
        $this->registerAdditionalMenuItems($controllerNames);
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

    /**
     * Returns the names of all available admin controllers
     *
     * @return string[]
     */
    private function getAdminControllerNames()
    {
        $names = array();
        $classFolder = $this->folder . 'classes/';
        if (!file_exists($classFolder)) {
            return $names;
        }
        $dirIter = new \DirectoryIterator($classFolder);
        foreach ($dirIter as $item) {
            if (preg_match('/^(.+)AdminController.php$/', $item->getBasename(), $matches)) {
                $names[] = $matches[1];
            }
        }
        sort($matches);
        return $names;
    }

    /**
     * Registers the additional (i.e. non-standard) menu items
     *
     * @param string[] $controllerNames
     *
     * @return void
     */
    private function registerAdditionalMenuItems($controllerNames)
    {
        global $sn;

        foreach ($controllerNames as $name) {
            if (in_array($name, array('Config', 'Default', 'Language', 'Stylesheet'))) {
                continue;
            }
            $url = "$sn?{$this->name}&admin=plugin_" . strtolower($name) . '&normal';
            XH_registerPluginMenuItem($this->name, $this->lang->get("menu_" . strtolower($name)), $url);
        }
    }

    /**
     * Returns the name of the requested admin controller
     *
     * @return string
     */
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

    /**
     * Returns the name of the requested action
     *
     * @return void
     */
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
     * @return $this
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

    /**
     * Registers a page controller
     *
     * A page controller is invoked when a certain page is requested.
     * This is mostly useful for plugins that wish to handle certain non
     * existing pages without the need for the user to actually create
     * these pages.
     *
     * The check whether the page is requested uses either $name directly,
     * or it uses the language string with key `page_$name` if it exists.
     * In the latter case, the page name is treated as it where a normal
     * page, i.e. it is HTML entitiy escaped and passed through `uenc`.
     *
     * @param string $name
     * @param string $actionParam
     *
     * @return $this
     */
    public function page($name, $actionParam = null)
    {
        global $su;

        if ($this->lang->get("page_$name")) {
            $page = uenc(htmlspecialchars($this->lang->get("page_$name"), ENT_COMPAT, 'UTF-8'));
        } else {
            $page = $name;
        }
        if ($su != $page) {
            return $this;
        }
        $controller = ucfirst($this->name) . '\\Default' . ucfirst($name) . 'PageController';
        $action = isset($_GET[$actionParam]) ? ucfirst($_GET[$actionParam]) : 'Default';
        $action = "handle$action";
        $controller = new $controller($this, $actionParam);
        ob_start();
        $controller->{$action}();
        Response::instance()->append(ob_get_clean());
        return $this;
    }
}

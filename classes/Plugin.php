<?php

/**
 * The plugin framework
 */
namespace Pfw;

/**
 * PFW plugins
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
    
    public static function instance($name)
    {
        return self::$instances[$name];
    }

    /**
     * Constructs an instance
     */
    public function __construct()
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

    public function func($name, $actionParam = null)
    {
        $controller = ucfirst($this->name) . '\\Default' . ucfirst($name) . 'FuncController';
        eval(<<<EOS
function {$this->name}_$name()
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
    }
}

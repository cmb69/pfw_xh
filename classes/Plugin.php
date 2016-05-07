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
        if (class_exists($controller)) { // TODO fall back to Pfw namespace!
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
}

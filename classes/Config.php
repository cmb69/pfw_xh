<?php

/**
 * The plugin framework.
 */
namespace Pfw;

/**
 * Access to the configuration of the plugins.
 *
 * For instance, to check whether the `autoload` option
 * of jQuery4CMSimple is enabled, do:
 *
 *      $config = Config::instance('jquery');
 *      if ($config->get('autoload')) {
 *          ...
 *      }
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */
class Config
{
    /**
     * The plugin.
     *
     * @var string
     */
    private $plugin;

    /**
     * Returns an instance.
     *
     * @param  string $plugin
     * @return self
     */
    public static function instance($plugin)
    {
        global $plugin_cf;
        static $instances = array();

        if (!isset($plugin_cf[$plugin])) {
            return null; // or exception or simply allow that?
        }
        if (!isset($instances[$plugin])) {
            $instances[$plugin] = new self($plugin);
        }
        return $instances[$plugin];
    }

    /**
     * Constructs an instance.
     *
     * @param string $plugin
     */
    private function __construct($plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Returns the value of a certain $key.
     *
     * @param  string $key
     * @return mixed
     * @internal
     */
    public function get($key)
    {
        global $plugin_cf;

        if (isset($plugin_cf[$this->plugin][$key])) {
            return $plugin_cf[$this->plugin][$key];
        } elseif (isset($plugin_cf['pfw'][$key])) {
            return $plugin_cf['pfw'][$key];
        } else {
            return null;
        }
    }
}

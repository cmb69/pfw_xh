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
 *      if ($config['autoload']) {
 *          ...
 *      }
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */
class Config implements \ArrayAccess
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
     * Returns whether a certain offset exists.
     *
     * @param  string $offset
     * @return bool
     * @internal
     */
    public function offsetExists($offset)
    {
        global $plugin_cf;

        return isset($plugin_cf[$this->plugin][$offset])
            || isset($plugin_cf['pfw'][$offset]);
    }

    /**
     * Returns the value of a certain offset.
     *
     * @param  string $offset
     * @return mixed
     * @internal
     */
    public function offsetGet($offset)
    {
        global $plugin_cf;

        if (isset($plugin_cf[$this->plugin][$offset])) {
            return $plugin_cf[$this->plugin][$offset];
        } elseif (isset($plugin_cf['pfw'][$offset])) {
            return $plugin_cf['pfw'][$offset];
        } else {
            return null;
        }
    }

    /**
     * Sets the value of a certain offset.
     *
     * @param  string $offset
     * @param  mixed  $value
     * @return void
     * @internal
     */
    public function offsetSet($offset, $value)
    {
        throw new \LogicException('Config options are write protected');
        echo $offset, $value; // to satisfy PHPMD
    }

    /**
     * Unsets a certain offset.
     *
     * @param  string $offset
     * @param  mixed  $value
     * @return void
     * @internal
     */
    public function offsetUnset($offset)
    {
        throw new \LogicException('Config options are write protected');
        echo $offset; // to satisfy PHPMD
    }
}

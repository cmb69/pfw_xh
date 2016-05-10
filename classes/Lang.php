<?php

/**
 * The plugin framework
 */
namespace Pfw;

/**
 * Access to the i18n of the plugins.
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */
class Lang
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
        global $plugin_tx;
        static $instances = array();

        if (!isset($plugin_tx[$plugin])) {
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
    public function __construct($plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Returns the value of a certain offset.
     *
     * @param  string $key
     * @return mixed
     * @internal
     */
    public function get($key)
    {
        global $plugin_tx;

        if (isset($plugin_tx[$this->plugin][$key])) {
            return $plugin_tx[$this->plugin][$key];
        } elseif (isset($plugin_tx['pfw'][$key])) {
            return $plugin_tx['pfw'][$key];
        } else {
            return null;
        }
    }
    /**
     * Returns a language text.
     *
     * printf-style placeholders are replaced by additional parameters.
     *
     * @param  string $key
     * @param  array  ...$args
     * @return string
     */
    public function singular($key)
    {
        $args = array_slice(func_get_args(), 1);
        return vsprintf($this->get($key), $args);
    }

    /**
     * Returns a pluralized language text.
     *
     * printf-style placeholders are replaced by additional parameters.
     *
     * @param  string $key
     * @param  int    $count
     * @param  array  ...$args
     * @return string
     */
    public function plural($key, $count)
    {
        if ($count == 1) {
            $suffix = '_singular';
        } elseif ($count > 2 && $count < 5) {
            $suffix = '_paucal';
            if ($this->get("$key$suffix") === null) {
                $suffix = '_plural';
            }
        } else {
            $suffix = '_plural';
        }
        $args = array_slice(func_get_args(), 1);
        return vsprintf($this->get("$key$suffix"), $args);
    }
}

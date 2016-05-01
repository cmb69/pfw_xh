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
class Lang implements \ArrayAccess
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
        global $plugin_tx;

        return isset($plugin_tx[$this->plugin][$offset])
            || isset($plugin_tx['pfw'][$offset]);
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
        global $plugin_tx;

        if (isset($plugin_tx[$this->plugin][$offset])) {
            return $plugin_tx[$this->plugin][$offset];
        } elseif (isset($plugin_tx['pfw'][$offset])) {
            return $plugin_tx['pfw'][$offset];
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
        return vsprintf($this[$key], $args);
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
            if (!isset($this["$key$suffix"])) {
                $suffix = '_plural';
            }
        } else {
            $suffix = '_plural';
        }
        $args = array_slice(func_get_args(), 1);
        return vsprintf($this["$key$suffix"], $args);
    }
}

<?php

/**
 * The plugin framework.
 */
namespace Pfw;

/**
 * Encapsulates the current HTTP request.
 *
 * You can access this {@see Singleton} ...
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */
class Request implements Singleton
{
    /**
     * Returns the single instance.
     *
     * @return self
     */
    public static function instance()
    {
        static $instance;

        if (!isset($instance)) {
            $instance = new self();
        }
        return $instance;
    }

    /**
     * Constructs an instance.
     */
    private function __construct()
    {
    }

    /**
     * Returns the current request method.
     *
     * @return string
     */
    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Returns the current URL.
     *
     * @return Url
     */
    public function url()
    {
        global $sn;

        parse_str($_SERVER['QUERY_STRING'], $params);
        return new Url($sn, $params);
    }
}

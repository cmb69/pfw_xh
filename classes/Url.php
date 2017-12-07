<?php

/*
 * Copyright 2016-2017 Christoph M. Becker
 *
 * This file is part of Pfw_XH.
 *
 * Pfw_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Pfw_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Pfw_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Pfw;

/**
 * CMSimple_XH internal URLs as value objects.
 *
 * `Url` value objects are most useful to manipulate certain query string
 * parameters of the requested URL, but can also be used to construct other URLs
 * of the CMSimple_XH installation.  At least for CMSimple_XH internal URLs they
 * should be preferred over manual URL construction which is error prone and too
 * concrete (consider clean URLs). Furthermore, the `Url` objects let you focus
 * on removing and adding query string parameters as needed, instead of simply
 * ignoring parameters of other plugins.
 *
 * Typical usage example:
 *
 *     $url = Url::getCurrent()    // get current URL
 *         ->without('foo')        // remove the `foo` parameter
 *         ->with('bar', 'baz');   // add a `bar` parameter with value `baz`
 *     $urlString = (string) $url;
 */
class Url
{
    /**
     * Return the URL of the current request.
     *
     * @return self
     */
    public static function getCurrent()
    {
        global $sn, $su;

        if ($su) {
            $params = array_slice($_GET, 1);
        } else {
            $params = $_GET;
        }
        return new self($sn, $su, $params);
    }

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $page;

    /**
     * @var array
     */
    private $params;

    /**
     * Constructs an internal URL.
     *
     * `$path` is supposed to be percent encoded.  `$page` is the percent
     * encoded query string parameter of a CMSimple_XH page (explicit or
     * implicit); if no particular page is requested, pass an empty string.
     * `$params` is an associative array of additional query string parameters
     * in `$_GET` style.
     *
     * @param string $path
     * @param string $page
     */
    private function __construct($path, $page = '', array $params = [])
    {
        $this->path = $path;
        $this->page = $page;
        $this->params = $params;
    }

    /**
     * Convenience wrapper for getRelative().
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getRelative();
    }

    /**
     * Return the "relative" URL string.
     *
     * Actually, this method returns an absolute URL string, which is not fully
     * qualified, though; it consists of path and query string only.
     *
     * @return string
     */
    public function getRelative()
    {
        $result = $this->path;
        $queryString = $this->getQueryString();
        if ($queryString) {
            $result .= "?$queryString";
        }
        return $result;
    }

    /**
     * Return the fully qualified absoulte URL string.
     *
     * @return string
     */
    public function getAbsolute()
    {
        $result = CMSIMPLE_URL;
        $queryString = $this->getQueryString();
        if ($queryString) {
            $result .= "?$queryString";
        }
        return $result;
    }

    /**
     * @return string
     */
    private function getQueryString()
    {
        $result = "{$this->page}";
        $additional = preg_replace('/=(?=&|$)/', '', http_build_query($this->params, null, '&'));
        if ($additional) {
            $result .= "&$additional";
        }
        return $result;
    }

    /**
     * Add or replace a query string parameter.
     *
     * If the `$param` query string parameter already exists, it is replaced
     * with the new `$value` (which may be a string or an array).  Otherwise the
     * query parameter is added to the URL.
     *
     * @param string $param
     * @param mixed $value
     * @return self
     */
    public function with($param, $value)
    {
        $params = $this->params;
        $params[$param] = $value;
        return new self($this->path, $this->page, $params);
    }

    /**
     * Remove a query string parameter.
     *
     * The `$param` query string parameter is removed if it exists.  Otherwise
     * the method silently succeeds.
     *
     * @param string $param
     * @return self
     */
    public function without($param)
    {
        $params = $this->params;
        unset($params[$param]);
        return new self($this->path, $this->page, $params);
    }
}

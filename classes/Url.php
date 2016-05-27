<?php

/*
Copyright 2016 Christoph M. Becker
 
This file is part of Pfw_XH.

Pfw_XH is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Pfw_XH is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Pfw_XH.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace Pfw;

/**
 * Internal URLs as immutable value objects
 *
 * Dealing with URLs is a very important part of web applications in general,
 * and CMSimple_XH plugins in particular. For CMSimple_XH plugins the relevant
 * URL component is the query string. Traditionally, most plugins simply
 * use string processing to assemble the desired query strings, what works
 * fine as long as the URLs won't change. However, there's still the problem
 * that CMSimple_XH has to stick with certain URL conventions, even if they
 * turned out to be a problem; for instance, name a CMSimple_XH page "print".
 *
 * One of the main motivations for having a plugin framework was to abstract
 * the URL handling as much as possible. This is realized by Plugin acting as
 * router and the controllers, and hopefully
 * sufficiently supported by this class. Manual construction of URLs via
 * string manipulation is therefore highly discouraged. Instead use with()
 * and without() an Request::url() or preferably where possible, Controller::url().
 * This is also important to not incidentially mess around with query
 * parameters set and required by other plugins. Only set and remove your
 * own parameters.
 */
class Url
{
    /**
     * The URL path
     *
     * @var string
     */
    private $path;

    /**
     * The URL params
     *
     * @var array
     */
    private $params;

    /**
     * Constructs an instance
     *
     * @param string $path
     * @param array  $params
     */
    public function __construct($path, array $params)
    {
        $this->path = $path;
        $this->params = $params;
    }

    /**
     * Converts an instance to string representing the relative URL
     *
     * @return string
     */
    public function __toString()
    {
        return $this->relative();
    }

    /**
     * Returns the relative URL
     *
     * @return string
     */
    public function relative()
    {
        return $this->path . '?' . $this->queryString();
    }

    /**
     * Returns the absolute URL
     *
     * @return string
     */
    public function absolute()
    {
        return CMSIMPLE_URL . '?' . $this->queryString();
    }

    /**
     * Returns the assembled query string
     *
     * @return string
     */
    private function queryString()
    {
        $params = array_map(
            function ($name, $value) {
                if (!empty($value)) {
                    return "$name=$value";
                } else {
                    return $name;
                }
            },
            array_keys($this->params),
            array_values($this->params)
        );
        return implode('&', $params);
    }

    /**
     * Returns a new URL with a certain param set.
     *
     * @param string $param
     * @param string $value
     *
     * @return self
     */
    public function with($param, $value)
    {
        $params = $this->params;
        $params[$param] = $value;
        return new self($this->path, $params);
    }

    /**
     * Returns a new URL without a certain param
     *
     * @param string $param
     *
     * @return self
     */
    public function without($param)
    {
        $params = $this->params;
        unset($params[$param]);
        return new self($this->path, $params);
    }
}

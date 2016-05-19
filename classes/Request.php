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
 * The current HTTP request
 *
 * You can access this {@see Singleton} ...
 */
class Request implements Singleton
{
    /**
     * Returns the single instance
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
     * Constructs an instance
     */
    private function __construct()
    {
    }

    /**
     * Returns the current request method
     *
     * @return string
     */
    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Returns the current URL
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

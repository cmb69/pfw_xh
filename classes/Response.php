<?php

/*
Copyright 2016-2017 Christoph M. Becker
 
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
 * The current HTTP response.
 */
class Response
{
    /**
     * Appends HTML to the contents area
     *
     * @param  string $html
     * @return void
     */
    public function append($html)
    {
        global $o;

        $o .= $html;
    }

    /**
     * Appends HTML to the head
     *
     * @param  string $html
     * @return void
     */
    public function appendToHead($html)
    {
        global $hjs;

        $hjs .= $html;
    }

    /**
     * Appends HTML to the body
     *
     * @param  string $html
     * @return void
     */
    public function appendToBody($html)
    {
        global $bjs;

        $bjs .= $html;
    }

    /**
     * Adds a styleheet link
     *
     * @param  string $path
     * @return void
     */
    public function addStylesheet($path)
    {
        $html = sprintf('<link rel="stylesheet" type="text/css" href="%s">', $path);
        $this->appendToHead($html);
    }

    /**
     * Adds a script
     *
     * @param  string $path
     * @return void
     */
    public function addScript($path)
    {
        $html = sprintf('<script type="text/javascript" src="%s"', $path);
        $this->appendToBody($html);
    }

    /**
     * Sets the document's title
     *
     * @param  string $value
     * @return void
     */
    public function setTitle($value)
    {
        global $title;

        $title = $value;
    }

    /**
     * Redirects to a URL with a certain status code and exit script.
     *
     * @param string $url
     * @param int    $code
     *
     * @return void
     */
    public function redirect($url, $code = 302)
    {
        header("Location: $url", $code);
        XH_exit();
    }
}

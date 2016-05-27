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
 * Simple value objects which encapsulate an HTML string.
 *
 * Encapsulating any HTML fragment as HtmlString is useful if such strings
 * will be `echo`d by a HtmlView, because HtmlView::escape() doesn't
 * escape HtmlStrings again.  This way we can always call HtmlView::escape()
 * regardless of whether we're dealing with text or HTML strings.
 */
class HtmlString
{
    private $contents;

    /**
     * Constructs an instance.
     *
     * @param string $contents
     */
    public function __construct($contents)
    {
        $this->contents = $contents;
    }

    /**
     * Returns the string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->contents;
    }
}

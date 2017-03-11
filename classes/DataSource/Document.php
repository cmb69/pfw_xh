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

namespace Pfw\DataSource;

/**
 * Documents for the @ref DocumentStore "DocumentStores".
 *
 * A document carries some arbitrary text contents and a token.
 * The contents is what is read from and written to the file.
 * The token is some opaque value specifying the version of the file contents.
 */
class Document
{
    /**
.     * @var string
     */
    private $contents;

    /**
     * @var mixed
     */
    private $token;

    /**
     * Constructs an instance.
     *
     * @param string $contents
     * @param mixed  $token
     */
    public function __construct($contents, $token = null)
    {
        $this->contents = $contents;
        $this->token = $token;
    }

    /**
     * Returns the token.
     *
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Returns the contents.
     *
     * @return string
     */
    public function getContents()
    {
        return $this->contents;
    }
}

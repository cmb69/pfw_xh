<?php

/*
 * Copyright 2017 Christoph M. Becker
 *
 * This file is part of the Pfw_XH SDK.
 *
 * The Pfw_XH SDK is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * The Pfw_XH SDK is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with the Pfw_XH SDK.  If not, see <http://www.gnu.org/licenses/>.
 */

class Scanner
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @var array
     */
    private $tokens;

    /**
     * @var int
     */
    private $position;

    /**
     * @param string
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
        $this->tokens = token_get_all(file_get_contents($filename));
        $this->position = 0;
    }

    /**
     * @return Symbol
     */
    public function getNext()
    {
        do {
            if ($this->position >= count($this->tokens)) {
                $sym = new Symbol(-1);
                break;
            }
            $sym = new Symbol($this->tokens[$this->position++]);
        } while ($sym->getKind() == T_WHITESPACE);
        return $sym;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }
}

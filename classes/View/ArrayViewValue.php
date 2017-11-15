<?php

/*
 * Copyright 2017 Christoph M. Becker
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

namespace Pfw\View;

use Iterator;

final class ArrayViewValue extends ViewValue implements Iterator
{
    /**
     * @return mixed
     */
    public function current()
    {
        return self::create($this->view_, current($this->value_));
    }

    /**
     * @return int
     */
    public function key()
    {
        return self::create($this->view_, key($this->value_));
    }

    /**
     * @return void
     */
    public function next()
    {
        next($this->value_);
    }

    /**
     * @return void
     */
    public function rewind()
    {
        reset($this->value_);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return key($this->value_) !== null;
    }
}

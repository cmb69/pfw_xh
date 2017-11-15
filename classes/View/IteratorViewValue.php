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

final class IteratorViewValue extends ViewValue implements Iterator
{
    /**
     * @return mixed
     */
    public function current()
    {
        return self::create($this->view_, $this->value_->current());
    }

    /**
     * @return int
     */
    public function key()
    {
        return self::create($this->view_, $this->value_->key());
    }

    /**
     * @return void
     */
    public function next()
    {
        $this->value_->next();
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->value_->rewind();
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->value_->valid();
    }
}

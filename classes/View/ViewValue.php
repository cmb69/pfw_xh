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

class ViewValue
{
    /**
     * @var View
     */
    protected $view_;

    /**
     * @var mixed
     */
    protected $value_;
    
    /**
     * @param mixed $value
     * @return self
     */
    public static function create(View $view, $value)
    {
        if (is_bool($value)) {
            return $value;
        } elseif (is_array($value)) {
            return new ArrayViewValue($view, $value);
        } elseif ($value instanceof Iterator) {
            return new IteratorViewValue($view, $value);
        } elseif ($value instanceof View) {
            return new NestedViewValue($view, $value);
        } elseif (is_object($value)) {
            return new ObjectViewValue($view, $value);
        } else {
            return new ViewValue($view, $value);
        }
    }

    /**
     * @param mixed $value
     */
    public function __construct(View $view, $value)
    {
        $this->view_ = $view;
        $this->value_ = $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->view_->escape($this->value_);
    }
}

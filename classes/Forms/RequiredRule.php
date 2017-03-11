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

namespace Pfw\Forms;

use XMLWriter;

/**
 * Rules stating a non-empty requirement
 *
 * @link https://www.w3.org/TR/html5/forms.html#the-required-attribute
 */
class RequiredRule extends Rule
{
    /**
     * Renders the rule as attribute of the given element
     *
     * @param XMLWriter $writer
     *
     * @return void
     */
    public function render(XMLWriter $writer)
    {
        $writer->writeAttribute('required', 'required');
    }

    /**
     * Returns whether the rule is fulfilled
     *
     * @param float $value
     *
     * @return bool
     */
    public function validate($value)
    {
        return $value != '';
    }

    /**
     * Renders a validation error message
     *
     * @param float     $value The actual value
     * @param XMLWriter $writer
     *
     * @return void
     */
    public function renderValidationError($value, XMLWriter $writer)
    {
        $this->doRenderValidationError(
            $this->lang->singular('validation_required', $this->control->label()),
            $writer
        );
    }
}

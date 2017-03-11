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

namespace Pfw\Forms;

use XMLWriter;

/**
 * Checkbox input form controls
 */
class CheckboxControl extends Control
{
    /**
     * Renders the control
     *
     * Unchecked checkboxes are ignored by user agents when submitting a form,
     * so we use the customary hack of having a hidden input with the same
     * name just before the checkbox. Thanks for svasti to making me aware
     * of this trick.
     *
     * @param XMLWriter $writer
     *
     * @return void
     */
    public function render(XMLWriter $writer)
    {
        $writer->startElement('div');
        $this->renderLabel($writer);
        $writer->startElement('input');
        $writer->writeAttribute('type', 'hidden');
        $writer->writeAttribute('name', $this->name());
        $writer->writeAttribute('value', '');
        $writer->endElement();
        $writer->startElement('input');
        $writer->writeAttribute('id', $this->id());
        $writer->writeAttribute('type', 'checkbox');
        $writer->writeAttribute('name', $this->name());
        $writer->writeAttribute('value', '1');
        if ($this->data()) {
            $writer->writeAttribute('checked', 'checked');
        }
        $writer->endElement();
        $this->renderRuleAttributes($writer);
        $this->renderValidationErrors($writer);
        $writer->endElement();
    }
}

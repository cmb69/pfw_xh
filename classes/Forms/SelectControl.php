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

namespace Pfw\Forms;

/**
 * Select form controls
 */
class SelectControl extends Control
{
    /**
     * The options
     *
     * @var string[]
     */
    private $options = array();

    /**
     * Adds an option
     *
     * @param string $option
     *
     * @return void
     */
    public function addOption($option)
    {
        $this->options[] = $option;
    }

    /**
     * Renders the control
     *
     * @param \SimpleXMLElement $form
     *
     * @return void
     */
    public function render(\SimpleXMLElement $form)
    {
        $field = $form->addChild('div');
        $this->renderLabel($field);
        $select = $field->addChild('select');
        $select->addAttribute('id', $this->id());
        $select->addAttribute('name', $this->name());
        foreach ($this->options as $option) {
            $option = $select->addChild('option', $option);
            if ($this->data() == $option) {
                $option->addAttribute('selected', 'selected');
            }
        }
        $this->renderRuleAttributes($select);
        $this->renderValidationErrors($field);
    }
}

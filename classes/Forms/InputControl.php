<?php

/**
 * Simple input form controls
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw\Forms;

/**
 * Simple input form controls
 */
class InputControl extends Control
{
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
        $input = $field->addChild('input');
        $input->addAttribute('id', $this->id());
        $this->renderTypeAttribute($input);
        $input->addAttribute('name', $this->name());
        $input->addAttribute('value', $this->data());
        $this->renderRuleAttributes($input);
        $this->renderValidationErrors($field);
    }
}

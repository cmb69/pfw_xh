<?php

/**
 * Checkbox input form controls
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw\Forms;

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
     * @param Form $form
     *
     * @return void
     */
    public function render(\SimpleXMLElement $form)
    {
        $field = $form->addChild('div');
        $this->renderLabel($field);
        $hidden = $field->addChild('input');
        $hidden->addAttribute('type', 'hidden');
        $hidden->addAttribute('name', $this->name());
        $hidden->addAttribute('value', '');
        $checkbox = $field->addChild('input');
        $checkbox->addAttribute('id', $this->id());
        $checkbox->addAttribute('type', 'checkbox');
        $checkbox->addAttribute('name', $this->name());
        $checkbox->addAttribute('value', '1');
        if ($this->data()) {
            $checkbox->addAttribute('checked', 'checked');
        }
        $this->renderRuleAttributes($checkbox);
        $this->renderValidationErrors($field);
    }
}

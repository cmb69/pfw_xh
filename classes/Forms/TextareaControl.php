<?php

/**
 * Textarea form controls
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw\Forms;

/**
 * Textarea form controls
 */
class TextareaControl extends Control
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
        $textarea = $field->addChild('textarea', $this->data());
        $textarea->addAttribute('id', $this->id());
        $textarea->addAttribute('name', $this->name());
        $this->renderRuleAttributes($textarea);
        $this->renderValidationErrors($field);
    }
}

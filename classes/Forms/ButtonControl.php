<?php

/**
 * Button form controls
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw\Forms;

/**
 * Button form controls
 */
class ButtonControl extends Control
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
        $label = "label_{$this->name}";
        $button = $field->addChild('button', $this->lang->singular($label));
        $button->addAttribute('name', $this->name());
    }
}

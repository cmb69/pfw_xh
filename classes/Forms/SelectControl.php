<?php

/**
 * CSRF protection form controls
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
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
     * @var array<string>
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

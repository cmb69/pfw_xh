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
 * Form controls
 */
abstract class Control
{
    /**
     * The form
     *
     * @var Form $form
     */
    protected $form;

    /**
     * The language
     *
     * @var Lang $lang
     */
    protected $lang;

    /**
     * The name without prefix
     *
     * @var string $name
     */
    protected $name;

    /**
     * The rules to apply
     *
     * @var array<Rule> $rules
     */
    protected $rules;

    /**
     * The ID without prefix
     *
     * @var int $id
     */
    private $id;

    /**
     * Constructs an instance
     *
     * @param Form      $form
     * @param \Pfw\Lang $lang
     * @param string    $name
     */
    public function __construct(Form $form, \Pfw\Lang $lang, $name)
    {
        static $id = 0;

        $this->form = $form;
        $this->lang = $lang;
        $this->name = $name;
        $this->rules = [];
        $this->id = ++$id;
    }

    /**
     * Returns the prefixed name
     *
     * @return string
     */
    public function getName()
    {
        return $this->form->getPrefix() . '_' . $this->name;
    }

    /**
     * Returns the current value
     *
     * @return string
     *
     * @todo Rename to `value`? That might be misleading wrt. `<textarea>`s.
     *       `data` isn't more clarifying, though.
     */
    protected function getData()
    {
        return $this->form->getData($this->name);
    }

    /**
     * Returns the prefixed ID
     *
     * @return string
     */
    protected function getId()
    {
        return 'pfw_control_' . $this->id;
    }

    /**
     * Adds a rule
     *
     * @param Rule $rule
     *
     * @return void
     */
    public function addRule(Rule $rule)
    {
        $this->rules[] = $rule;
    }

    /**
     * Renders the control
     *
     * The control is rendered inside a `<div>` with a `<label>` and
     * additional elements, where appropriate.
     *
     * @param XMLWriter $writer
     *
     * @return void
     */
    abstract public function render(XMLWriter $writer);

    /**
     * Renders the control's label
     *
     * @param XMLWriter $writer
     *
     * @return void
     */
    protected function renderLabel(XMLWriter $writer)
    {
        $writer->startElement('label');
        $writer->writeAttribute('for', $this->getId());
        $writer->text($this->label());
        $writer->endElement();
    }

    /**
     * Returns the label
     *
     * The label's text is taken from the language file by looking for the
     * key `label_$name`.
     *
     * @return string
     *
     * @todo What if there's no such language string?
     *       Have an empty label (as it's now), throw a notice for the
     *       developer and/or use the control's name directly.
     */
    public function label()
    {
        return $this->lang->singular("label_{$this->name}");
    }

    /**
     * Renders the attributes of all attached rules
     *
     * @param XMLWriter $writer
     *
     * @return void
     */
    protected function renderRuleAttributes(XMLWriter $writer)
    {
        foreach ($this->rules as $rule) {
            $rule->render($writer);
        }
    }

    /**
     * Returns whether the current value of the control is valid
     *
     * @return bool
     */
    public function validate()
    {
        foreach ($this->rules as $rule) {
            if (!$rule->validate($this->getData())) {
                return false;
            }
        }
        return true;
    }

    /**
     * Renders the validation error messages, if any
     *
     * @param XMLWriter $writer
     *
     * @return void
     */
    public function renderValidationErrors(XMLWriter $writer)
    {
        if ($this->form->isValidated()) {
            foreach ($this->rules as $rule) {
                if (!$rule->validate($this->getData())) {
                    $rule->renderValidationError($this->getData(), $writer);
                }
            }
        }
    }
}

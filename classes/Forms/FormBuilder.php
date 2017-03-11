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

/**
 * %Form builders
 *
 * The form [builder](http://martinfowler.com/bliki/ExpressionBuilder.html)
 * offers a fluent interface for creation of forms.
 * You can freely mix controls and rules, whereby rules automatically
 * are applied to the most recent control.
 *
 * All controls accept a $name parameter.
 * This is used to build the value of the name attribute of the form control,
 * prefixed by the $prefix given to the constructor.
 * The $name is also used to get the respective label from the language file,
 * prefixed by label_
 *
 * The controls and rules are modelled according to the HTML5 specification.
 * This way validation on the client side occurs, if supported by the browser.
 * Of course, all validation is done on the server side nonetheless.
 *
 * An example from a Controller method:
 *
 *      // create a form builder
 *      $builder = $this->formBuilder('./')
 *          // add a required text input
 *          ->text('name')->required()
 *          // add a number input which checks that the value is at least 18
 *          ->number('age')->min(18)
 *          // add a submit button
 *          ->button('save')
 *          // return the form
 *          ->build();
 *
 * @todo Rename `render*` where appropriate. Actually, we usually add elements
 *       or attributes.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class FormBuilder
{
    /**
     * The form to build
     *
     * @var Form
     */
    private $form;

    /**
     * The language
     *
     * @var \Pfw\Lang
     */
    private $lang;

    /**
     * The current (aka. most recently added) control
     *
     * Rules are always added to this control.
     *
     * @var Control
     */
    private $currentControl;

    /**
     * Constructs an instance
     *
     * @param string $prefix
     * @param \Pfw\Lang   $lang
     * @param string $action
     *
     * @todo Use other parameters, e.g. `Control` and `Url`?
     */
    public function __construct($prefix, \Pfw\Lang $lang, $action)
    {
        $this->form = new Form($prefix, $lang, $action);
        $this->lang = $lang;
    }

    /**
     * Adds a hidden input form control.
     *
     * @param string $name
     * @return $this
     */
    public function hidden($name)
    {
        return $this->addControl(new HiddenControl($this->form, $this->lang, $name));
    }

    /**
     * Adds a text input form control.
     *
     * @param string $name
     * @return $this
     */
    public function text($name)
    {
        return $this->addControl(new TextControl($this->form, $this->lang, $name));
    }

    /**
     * Adds a password input form control
     *
     * @param string $name
     * @return $this
     */
    public function password($name)
    {
        return $this->addControl(new PasswordControl($this->form, $this->lang, $name));
    }

    /**
     * Adds a number input form control
     *
     * @param string $name
     * @return $this
     */
    public function number($name)
    {
        return $this->addControl(new NumberControl($this->form, $this->lang, $name));
    }

    /**
     * Adds a checkbox input form control
     *
     * @param string $name
     * @return $this
     */
    public function checkbox($name)
    {
        return $this->addControl(new CheckboxControl($this->form, $this->lang, $name));
    }

    /**
     * Adds a submit button form control
     *
     * @param string $name
     * @return $this
     */
    public function button($name)
    {
        return $this->addControl(new ButtonControl($this->form, $this->lang, $name));
    }

    /**
     * Adds a select form control
     *
     * @param string $name
     * @return $this
     */
    public function select($name)
    {
        return $this->addControl(new SelectControl($this->form, $this->lang, $name));
    }

    /**
     * Adds option elements.
     *
     * The options may be given either as a single array, or as a variable
     * argument list consisting of strings.
     *
     * @param array<string> $options
     * @return $this
     */
    public function options($options)
    {
        if (!is_array($options)) {
            $options = func_get_args();
        }
        foreach ($options as $option) {
            $this->currentControl->addOption($option);
        }
        return $this;
    }

    /**
     * Adds a textarea form control
     *
     * @param string $name
     * @return $this
     */
    public function textarea($name)
    {
        return $this->addControl(new TextareaControl($this->form, $this->lang, $name));
    }

    /**
     * Adds a CSRF protection token input element
     *
     * The CSRF token is automatically validated on form::validate().
     *
     * @return $this
     */
    public function csrf()
    {
        return $this->addControl(new CSRFControl($this->form, $this->lang, null));
    }

    /**
     * Adds a control
     *
     * @param Control $control
     * @return $this
     */
    private function addControl(Control $control)
    {
        $this->currentControl = $control;
        $this->form->addControl($control);
        return $this;
    }

    /**
     * Adds a minlength rule
     *
     * @param int $length
     * @return $this
     */
    public function minlength($length)
    {
        return $this->addRule(new MinlengthRule($this->currentControl, $this->lang, $length));
    }

    /**
     * Adds a maxlength rule
     *
     * @param int $length
     * @return $this
     */
    public function maxlength($length)
    {
        return $this->addRule(new MaxlengthRule($this->currentControl, $this->lang, $length));
    }

    /**
     * Adds a required rule
     *
     * @return $this
     */
    public function required()
    {
        return $this->addRule(new RequiredRule($this->currentControl, $this->lang));
    }

    /**
     * Adds a pattern rule
     *
     * @param string $pattern
     * @return $this
     */
    public function pattern($pattern)
    {
        return $this->addRule(new PatternRule($this->currentControl, $this->lang, $pattern));
    }

    /**
     * Adds a min rule
     *
     * @param number $value
     * @return $this
     */
    public function min($value)
    {
        return $this->addRule(new MinRule($this->currentControl, $this->lang, $value));
    }

    /**
     * Adds a max rule
     *
     * @param number $value
     * @return $this
     */
    public function max($value)
    {
        return $this->addRule(new MaxRule($this->currentControl, $this->lang, $value));
    }

    /**
     * Adds a rule
     *
     * @param Rule $rule
     * @return $this
     */
    private function addRule(Rule $rule)
    {
        $this->currentControl->addRule($rule);
        return $this;
    }

    /**
     * Returns the constructed form
     *
     * @return Form
     */
    public function build()
    {
        return $this->form;
    }
}

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
 * Form builders
 *
 * The form builder offers a fluent interface for creation of forms.
 * You can freely mix controls and rules, whereby rules automatically
 * are applied to the latest control.
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
 * An example:
 *
 *      // create a form builder
 *      $builder = new FormBuilder('plugin', Lang::instance('plugin'), './');
 *      // build the form
 *      $form = $builder
 *          // add a required text input
 *          ->text('name')->required()
 *          // add a number input which checks that the value is at least 18
 *          ->number('age')->min(18)
 *          // add a submit button
 *          ->button('save')
 *          // return the form
 *          ->build();
 *
 * @link http://martinfowler.com/bliki/ExpressionBuilder.html
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
     * Adds a hidden input form control
     *
     * @param string $name
     *
     * @return $this
     *
     * @link https://www.w3.org/TR/html5/forms.html#hidden-state-(type=hidden)
     */
    public function hidden($name)
    {
        return $this->control(new HiddenControl($this->form, $this->lang, $name));
    }

    /**
     * Adds a text input form control
     *
     * @param string $name
     *
     * @return $this
     *
     * @link https://www.w3.org/TR/html5/forms.html#text-(type=text)-state-and-search-state-(type=search)
     */
    public function text($name)
    {
        return $this->control(new TextControl($this->form, $this->lang, $name));
    }

    /**
     * Adds a password input form control
     *
     * @param string $name
     *
     * @return $this
     *
     * @link https://www.w3.org/TR/html5/forms.html#password-state-(type=password)
     */
    public function password($name)
    {
        return $this->control(new PasswordControl($this->form, $this->lang, $name));
    }

    /**
     * Adds a number input form control
     *
     * @param string $name
     *
     * @return $this
     *
     * @link https://www.w3.org/TR/html5/forms.html#number-state-(type=number)
     */
    public function number($name)
    {
        return $this->control(new NumberControl($this->form, $this->lang, $name));
    }

    /**
     * Adds a checkbox input form control
     *
     * @param string $name
     *
     * @return $this
     *
     * @link https://www.w3.org/TR/html5/forms.html#checkbox-state-(type=checkbox)
     */
    public function checkbox($name)
    {
        return $this->control(new CheckboxControl($this->form, $this->lang, $name));
    }

    /**
     * Adds a submit button form control
     *
     * @param string $name
     *
     * @return $this
     *
     * @link https://www.w3.org/TR/html5/forms.html#the-button-element
     */
    public function button($name)
    {
        return $this->control(new ButtonControl($this->form, $this->lang, $name));
    }

    /**
     * Adds a select form control
     *
     * @param string $name
     *
     * @return $this
     *
     * @link https://www.w3.org/TR/html5/forms.html#the-select-element
     */
    public function select($name)
    {
        return $this->control(new SelectControl($this->form, $this->lang, $name));
    }

    /**
     * Adds option elements
     *
     * @param string[] ...$options
     *
     * @return $this
     *
     * @link https://www.w3.org/TR/html5/forms.html#the-option-element
     */
    public function options()
    {
        foreach (func_get_args() as $option) {
            $this->currentControl->addOption($option);
        }
        return $this;
    }

    /**
     * Adds a textarea form control
     *
     * @param string $name
     *
     * @return $this
     *
     * @link https://www.w3.org/TR/html5/forms.html#the-textarea-element
     */
    public function textarea($name)
    {
        return $this->control(new TextareaControl($this->form, $this->lang, $name));
    }

    /**
     * Adds a CSRF protection token input element
     *
     * The CSRF token is automatically validated on form::validate().
     *
     * @return $this
     *
     * @link http://www.cmsimple-xh.org/dev-doc/php/XH/tutorial_XH_CSRFProtection.cls.html
     */
    public function csrf()
    {
        return $this->control(new CSRFControl($this->form, $this->lang, null));
    }

    /**
     * Adds a control
     *
     * @param Control $control
     *
     * @return $this
     */
    private function control(Control $control)
    {
        $this->currentControl = $control;
        $this->form->addControl($control);
        return $this;
    }

    /**
     * Adds a minlength rule
     *
     * @param int $length
     *
     * @link https://www.w3.org/TR/html5/forms.html#the-maxlength-and-minlength-attributes
     */
    public function minlength($length)
    {
        return $this->rule(new MinlengthRule($this->currentControl, $this->lang, $length));
    }

    /**
     * Adds a maxlength rule
     *
     * @param int $length
     *
     * @link https://www.w3.org/TR/html5/forms.html#the-maxlength-and-minlength-attributes
     */
    public function maxlength($length)
    {
        return $this->rule(new MaxlengthRule($this->currentControl, $this->lang, $length));
    }

    /**
     * Adds a required rule
     *
     * @link https://www.w3.org/TR/html5/forms.html#the-required-attribute
     */
    public function required()
    {
        return $this->rule(new RequiredRule($this->currentControl, $this->lang));
    }

    /**
     * Adds a pattern rule
     *
     * @param string $pattern
     *
     * @link https://www.w3.org/TR/html5/forms.html#the-pattern-attribute
     */
    public function pattern($pattern)
    {
        return $this->rule(new PatternRule($this->currentControl, $this->lang, $pattern));
    }

    /**
     * Adds a min rule
     *
     * @param number $value
     *
     * @link https://www.w3.org/TR/html5/forms.html#the-min-and-max-attributes
     */
    public function min($value)
    {
        return $this->rule(new MinRule($this->currentControl, $this->lang, $value));
    }

    /**
     * Adds a max rule
     *
     * @param number $value
     *
     * @link https://www.w3.org/TR/html5/forms.html#the-min-and-max-attributes
     */
    public function max($value)
    {
        return $this->rule(new MaxRule($this->currentControl, $this->lang, $value));
    }

    /**
     * Adds a rule
     *
     * @param Rule $rule
     *
     * @return $this
     */
    private function rule(Rule $rule)
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

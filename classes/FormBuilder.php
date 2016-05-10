<?php

/**
 * Forms and their builders
 *
 * @see <https://www.w3.org/TR/html5/forms.html>
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw;

/**
 * The form builder
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
     * @var Lang
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
     * @param Lang   $lang
     * @param string $action
     *
     * @todo Use other parameters, e.g. `Control` and `Url`?
     */
    public function __construct($prefix, Lang $lang, $action)
    {
        $this->form = new Form($prefix, $lang, $action);
        $this->lang = $lang;
    }

    /**
     * Adds a hidden input form control
     *
     * @param string $name
     *
     * @return self
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
     * @return self
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
     * @return self
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
     * @return self
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
     * @return self
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
     * @return self
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
     * @return self
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
     * @param array<string> ...$options
     *
     * @return self
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
     * @return self
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
     * @return self
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
     * @return self
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
     * @return self
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

/**
 * Simple HTML POST forms
 *
 * The class handles rendering and validation,
 * including displaying of error messages.
 *
 * Example:
 *
 *      if (Request::instance()->method() != 'POST') {
 *          // populate the form with some data
 *          $form->populate($data);
 *      } else {
 *          // validate the form and get the data
 *          $data = $form->validate();
 *          // in case of invalid data, $data is false
 *          if ($data) {
 *              // do something with the data, e.g. store in DB
 *              process($data);
 *              // immediately redirect on success
 *              Response::instance()->redirect($url);
 *          } else {
 *              // nothing to do here, as the form is already populated with
 *              // the posted data, which will be rendered below, including
 *              // all validation error messages
 *          }
 *      }
 *      // render the form
 *      echo $form->render();
 */
class Form
{
    /**
     * The prefix of all names
     *
     * @var string
     */
    private $prefix;

    /**
     * The language
     *
     * @var Lang
     */
    private $lang;

    /**
     * The form's action attribute value
     *
     * @var string
     */
    private $action;

    /**
     * The controls.
     * 
     * @var array<$string, Control>
     */
    private $controls;

    /**
     * The form data
     *
     * @var string[]
     */
    private $data;

    /**
     * Whether the form has already been validated
     *
     * @var bool
     */
    private $validated;

    /**
     * Constructs an instance
     *
     * @param string $prefix
     * @param Lang   $lang
     * @param string $action
     */
    public function __construct($prefix, Lang $lang, $action)
    {
        $this->prefix = $prefix;
        $this->lang = $lang;
        $this->action = $action;
        $this->controls = array();
        $this->data = array();
        $this->validated = false;
    }

    /**
     * Returns the name prefix
     *
     * @return string
     */
    public function prefix()
    {
        return $this->prefix;
    }

    /**
     * Adds a control
     *
     * @param Control $control
     *
     * @return void
     */
    public function addControl(Control $control)
    {
        $this->controls[$control->name()] = $control;
    }

    /**
     * Populates the form with data
     *
     * @param string[] $data A map
     *
     * @return void
     */
    public function populate(array $data)
    {
        $this->data = $data;
    }

    /**
     * Returns the data (aka. value) of a certain control
     *
     * @param string $key Actually, the name of the control
     *
     * @return string
     */
    public function data($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    /**
     * Renders the form including all validation errors,
     * if the form has been validated.
     *
     * @return string HTML
     */
    public function render()
    {
        $form = new \SimpleXMLElement('<form/>');
        $form->addAttribute('method', 'POST');
        $form->addAttribute('action', $this->action);
        $form->addAttribute('class', "{$this->prefix}_form");
        foreach ($this->controls as $control) {
            $control->render($form);
        }
        $element = dom_import_simplexml($form);
        return $element->C14N();
    }

    /**
     * Validates the posted form data, and returns them on success,
     * `false` otherwise.
     *
     * @return array|false
     */
    public function validate()
    {
        $this->populateFromPost();
        $this->validated = true;
        foreach ($this->controls as $control) {
            if (!$control->validate()) {
                return false;
            }
        }
        return $this->data;
    }
    
    /**
     * Returns whether the form has already been validated
     *
     * @return bool
     */
    public function validated()
    {
        return $this->validated;
    }

    /**
     * Populates the form data from $_POST
     *
     * Only $_POST parameters actually belonging to the form are incorporated.
     *
     * @return void
     */
    private function populateFromPost()
    {
        $prefix = "{$this->prefix}_";
        $this->data = array();
        foreach (array_keys($this->controls) as $name) {
            if (isset($_POST[$name])) {
                $key = substr($name, strlen($prefix));
                $this->data[$key] = stsl($_POST[$name]);
            }
        }
    }
}

/**
 * Form controls
 */
abstract class Control
{
    /**
     * The form
     *
     * @var Form
     */
    protected $form;

    /**
     * The language
     *
     * @var Lang
     */
    protected $lang;

    /**
     * The name without prefix
     *
     * @var string
     */
    protected $name;

    /**
     * The rules to apply
     *
     * @var array<Rule>
     */
    protected $rules;

    /**
     * The ID without prefix
     *
     * @var int
     */
    private $id;

    /**
     * Constructs an instance
     *
     * @param Form   $form
     * @param Lang   $lang
     * @param string $name
     */
    public function __construct(Form $form, Lang $lang, $name)
    {
        static $id = 0;

        $this->form = $form;
        $this->lang = $lang;
        $this->name = $name;
        $this->rules = array();
        $this->id = ++$id;
    }

    /**
     * Returns the prefixed name
     *
     * @return string
     */
    public function name()
    {
        return $this->form->prefix() . '_' . $this->name;
    }

    /**
     * Returns the current value
     *
     * @return string
     *
     * @todo Rename to `value`? That might be misleading wrt. `<textarea>`s.
     *       `data` isn't more clarifying, though.
     */
    protected function data()
    {
        return $this->form->data($this->name);
    }

    /**
     * Returns the prefixed ID
     *
     * @return string
     */
    protected function id()
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
     * @param \SimpleXMLElement $form
     *
     * @return void
     */
    abstract public function render(\SimpleXMLElement $form);

    /**
     * Renders the control's label
     *
     * @param \SimpleXMLElement $form
     *
     * @return void
     */
    protected function renderLabel(\SimpleXMLElement $form)
    {
        $label = $form->addChild('label', $this->label());
        $label->addAttribute('for', $this->id());
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
     * @param \SimpleXMLElement $control
     *
     * @return void
     */
    protected function renderRuleAttributes(\SimpleXMLElement $control)
    {
        foreach ($this->rules as $rule) {
            $rule->render($control);
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
            if (!$rule->validate($this->data())) {
                return false;
            }
        }
        return true;
    }

    /**
     * Renders the validation error messages, if any
     *
     * @param \SimpleXMLElement $field
     *
     * @return void
     */
    public function renderValidationErrors(\SimpleXMLElement $field)
    {
        if ($this->form->validated()) {
            foreach ($this->rules as $rule) {
                if (!$rule->validate($this->data())) {
                    $rule->renderValidationError($this->data(), $field);
                }
            }
        }
    }
}

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

/**
 * Text input form controls
 */
class TextControl extends InputControl
{
    /**
     * Renders the type attribute of the input
     *
     * @param \SimpleXMLElement $sxe
     *
     * @return void
     */
    public function renderTypeAttribute(\SimpleXMLElement $sxe)
    {
        $sxe->addAttribute('type', 'text');
    }
}

/**
 * Hidden input form controls
 */
class HiddenControl extends InputControl
{
    /**
     * Renders the type attribute of the input
     *
     * @param \SimpleXMLElement $sxe
     *
     * @return void
     */
    public function renderTypeAttribute(\SimpleXMLElement $sxe)
    {
        $sxe->addAttribute('type', 'hidden');
    }

    /**
     * Renders the control's label
     *
     * As hidden input controls don't need a label, we simply do nothing here.
     *
     * @param \SimpleXMLElement $form
     *
     * @return void
     */
    public function renderLabel(\SimpleXMLElement $form)
    {
        // do nothing
    }
}

/**
 * Number input form controls
 */
class NumberControl extends InputControl
{
    /**
     * Renders the type attribute of the input
     *
     * @param \SimpleXMLElement $sxe
     *
     * @return void
     */
    public function renderTypeAttribute(\SimpleXMLElement $sxe)
    {
        $sxe->addAttribute('type', 'number');
    }
}

/**
 * Password input form controls
 */
class PasswordControl extends InputControl
{
    /**
     * Renders the type attribute of the input
     *
     * @param \SimpleXMLElement $sxe
     *
     * @return void
     */
    public function renderTypeAttribute(\SimpleXMLElement $sxe)
    {
        $sxe->addAttribute('type', 'password');
    }
}

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

/**
 * CSRF protection form controls
 */
class CsrfControl extends Control
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
        global $_XH_csrfProtection;

        $html = $_XH_csrfProtection->tokenInput();
        preg_match('/value="([a-z0-9]+)"/', $html, $matches);
        $input = $form->addChild('input');
        $input->addAttribute('type', 'hidden');
        $input->addAttribute('name', 'xh_csrf_token');
        $input->addAttribute('value', $matches[1]);
    }

    /**
     * Returns whether the current value of the control is valid
     */
    public function validate()
    {
        global $_XH_csrfProtection;

        $_XH_csrfProtection->check();
        return true;
    }
}

/**
 * A rule regarding validation
 *
 * @todo Rename to `Constraint`? `Rule` is the name used by HTML5, though.
 */
abstract class Rule
{
    /**
     * The control the rule belongs to
     *
     * @var Control
     */
    protected $control;

    /**
     * The language
     *
     * @var Lang
     */
    protected $lang;

    /**
     * Constructs a rule
     *
     * @param Control $control
     * @param Lang    $lang
     */
    public function __construct(Control $control, Lang $lang)
    {
        $this->control = $control;
        $this->lang = $lang;
    }

    /**
     * Renders the rule as attribute of the given element
     *
     * @param \SimpleXMLElement $sxe
     *
     * @return void
     */
    abstract public function render(\SimpleXMLElement $sxe);
    
    /**
     * Returns whether the rule is fulfilled
     *
     * @param float $value
     *
     * @return bool
     */
    abstract public function validate($value);

    /**
     * Renders a validation error message
     *
     * @param float             $value The actual value
     * @param \SimpleXMLElement $field
     *
     * @return void
     */
    abstract public function renderValidationError($value, \SimpleXMLElement $field);
}

/**
 * A rule stating a minimum length requirement
 *
 * @link https://www.w3.org/TR/html5/forms.html#the-maxlength-and-minlength-attributes
 */
class MinlengthRule extends Rule
{
    /**
     * The minimum length
     *
     * @var int
     */
    private $length;

    /**
     * Constructs an instance
     *
     * @param Control $control
     * @param Lang    $lang
     * @param int     $length
     */
    public function __construct(Control $control, Lang $lang, $length)
    {
        parent::__construct($control, $lang);
        $this->length = $length;
    }

    /**
     * Renders the rule as attribute of the given element
     *
     * @param \SimpleXMLElement $sxe
     *
     * @return void
     */
    public function render(\SimpleXMLElement $sxe)
    {
        $sxe->addAttribute('minlength', $this->length);
    }

    /**
     * Returns whether the rule is fulfilled
     *
     * @param float $value
     *
     * @return bool
     */
    public function validate($value)
    {
        return utf8_strlen($value) >= $this->length;
    }

    /**
     * Renders a validation error message
     *
     * @param float             $value The actual value
     * @param \SimpleXMLElement $field
     *
     * @return void
     */
    public function renderValidationError($value, \SimpleXMLElement $field)
    {
        $text = $this->lang->singular('validation_minlength', $this->control->label(), $this->length);
        $div = $field->addChild('div', $text);
        $div->addAttribute('class', 'pfw_validation_error');
    }
}

/**
 * A rule stating a maximum length requirement
 *
 * @link https://www.w3.org/TR/html5/forms.html#the-maxlength-and-minlength-attributes
 */
class MaxlengthRule extends Rule
{
    /**
     * The maximum length
     *
     * @var int
     */
    private $length;

    /**
     * Constructs an instance
     *
     * @param Control $control
     * @param Lang    $lang
     * @param int     $length
     */
    public function __construct(Control $control, Lang $lang, $length)
    {
        parent::__construct($control, $lang);
        $this->length = $length;
    }

    /**
     * Renders the rule as attribute of the given element
     *
     * @param \SimpleXMLElement $sxe
     *
     * @return void
     */
    public function render(\SimpleXMLElement $sxe)
    {
        $sxe->addAttribute('maxlength', $this->length);
    }

    /**
     * Returns whether the rule is fulfilled
     *
     * @param float $value
     *
     * @return bool
     */
    public function validate($value)
    {
        return utf8_strlen($value) <= $this->length;
    }

    /**
     * Renders a validation error message
     *
     * @param float             $value The actual value
     * @param \SimpleXMLElement $field
     *
     * @return void
     */
    public function renderValidationError($value, \SimpleXMLElement $field)
    {
        $text = $this->lang->singular('validation_maxlength', $this->control->label(), $this->length);
        $div = $field->addChild('div', $text);
        $div->addAttribute('class', 'pfw_validation_error');
    }
}

/**
/**
 * A rule stating a non-empty requirement
 *
 * @link https://www.w3.org/TR/html5/forms.html#the-required-attribute
 */
class RequiredRule extends Rule
{
    /**
     * Renders the rule as attribute of the given element
     *
     * @param \SimpleXMLElement $sxe
     *
     * @return void
     */
    public function render(\SimpleXMLElement $sxe)
    {
        $sxe->addAttribute('required', 'required');
    }

    /**
     * Returns whether the rule is fulfilled
     *
     * @param float $value
     *
     * @return bool
     */
    public function validate($value)
    {
        return $value != '';
    }

    /**
     * Renders a validation error message
     *
     * @param float             $value The actual value
     * @param \SimpleXMLElement $field
     *
     * @return void
     */
    public function renderValidationError($value, \SimpleXMLElement $field)
    {
        $text = $this->lang->singular('validation_required', $this->control->label());
        $div = $field->addChild('div', $text);
        $div->addAttribute('class', 'pfw_validation_error');
    }
}

/**
 * A rule stating a pattern requirement
 *
 * To be able to do client and server side validation of the value against
 * the regular expression pattern, only the intersection of features
 * supported by JavaScript and PCRE may be used.
 *
 * @todo Find out what this intersection is.
 *
 * @link https://www.w3.org/TR/html5/forms.html#the-pattern-attribute
 */
class PatternRule extends Rule
{
    /**
     * The regex pattern
     *
     * @var string
     */
    private $pattern;

    /**
     * Constructs an instance
     *
     * @param Control $control
     * @param Lang    $lang
     * @param string  $pattern
     */
    public function __construct(Control $control, Lang $lang, $pattern)
    {
        parent::__construct($control, $lang);
        $this->pattern = $pattern;
    }

    /**
     * Renders the rule as attribute of the given element
     *
     * @param \SimpleXMLElement $sxe
     *
     * @return void
     */
    public function render(\SimpleXMLElement $sxe)
    {
        $sxe->addAttribute('pattern', $this->pattern);
    }

    /**
     * Returns whether the rule is fulfilled
     *
     * @param float $value
     *
     * @return bool
     */
    public function validate($value)
    {
        $pattern = "/^({$this->pattern})$/";
        return preg_match($pattern, $value);
    }

    /**
     * Renders a validation error message
     *
     * @param float             $value The actual value
     * @param \SimpleXMLElement $field
     *
     * @return void
     */
    public function renderValidationError($value, \SimpleXMLElement $field)
    {
        $text = $this->lang->singular('validation_pattern', $this->control->label(), $this->pattern);
        $div = $field->addChild('div', $text);
        $div->addAttribute('class', 'pfw_validation_error');
    }
}

/**
 * A rule stating a minimum value requirement
 *
 * @link https://www.w3.org/TR/html5/forms.html#the-min-and-max-attributes
 */
class MinRule extends Rule
{
    /**
     * The minimum value
     *
     * @var float
     */
    private $value;

    /**
     * Constructs an instance
     *
     * @param Control $control
     * @param Lang    $lang
     * @param float   $value
     */
    public function __construct(Control $control, Lang $lang, $value)
    {
        parent::__construct($control, $lang);
        $this->value = $value;
    }

    /**
     * Renders the rule as attribute of the given element
     *
     * @param \SimpleXMLElement $sxe
     *
     * @return void
     */
    public function render(\SimpleXMLElement $sxe)
    {
        $sxe->addAttribute('min', $this->value);
    }
    
    /**
     * Returns whether the rule is fulfilled
     *
     * @param float $value
     *
     * @return bool
     */
    public function validate($value)
    {
        return $value >= $this->value;
    }
    
    /**
     * Renders a validation error message
     *
     * @param float             $value The actual value
     * @param \SimpleXMLElement $field
     *
     * @return void
     */
    public function renderValidationError($value, \SimpleXMLElement $field)
    {
        $text = $this->lang->singular('validation_min', $this->control->label(), $this->value);
        $div = $field->addChild('div', $text);
        $div->addAttribute('class', 'pfw_validation_error');
    }
}

/**
/**
 * A rule stating a maximum value requirement
 *
 * @link https://www.w3.org/TR/html5/forms.html#the-min-and-max-attributes
 */
class MaxRule extends Rule
{
    /**
     * The minimum value
     *
     * @var float
     */
    private $value;

    /**
     * Constructs an instance
     *
     * @param Control $control
     * @param Lang    $lang
     * @param float   $value
     */
    public function __construct(Control $control, Lang $lang, $value)
    {
        parent::__construct($control, $lang);
        $this->value = $value;
    }

    /**
     * Renders the rule as attribute of the given element
     *
     * @param \SimpleXMLElement $sxe
     *
     * @return void
     */
    public function render(\SimpleXMLElement $sxe)
    {
        $sxe->addAttribute('max', $this->value);
    }

    /**
     * Returns whether the rule is fulfilled
     *
     * @param float $value
     *
     * @return bool
     */
    public function validate($value)
    {
        return $value <= $this->value;
    }

    /**
     * Renders a validation error message
     *
     * @param float             $value The actual value
     * @param \SimpleXMLElement $field
     *
     * @return void
     */
    public function renderValidationError($value, \SimpleXMLElement $field)
    {
        $text = $this->lang->singular('validation_max', $this->control->label(), $this->value);
        $div = $field->addChild('div', $text);
        $div->addAttribute('class', 'pfw_validation_error');
    }
}

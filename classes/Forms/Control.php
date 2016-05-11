<?php

/**
 * Form controls
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw\Forms;

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
     * @var \Pfw\Lang
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

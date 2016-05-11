<?php

/**
 * Rules stating a pattern requirement
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw\Forms;

use Pfw\Lang;

/**
 * Rules stating a pattern requirement
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

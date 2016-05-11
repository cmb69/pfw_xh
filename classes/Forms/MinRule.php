<?php

/**
 * Rules stating a minimum value requirement
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw\Forms;

use Pfw\Lang;

/**
 * Rules stating a minimum value requirement
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

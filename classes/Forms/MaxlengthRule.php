<?php

/**
 * Rules stating a maximum length requirement
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw\Forms;

use Pfw\Lang;

/**
 * Rules stating a maximum length requirement
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

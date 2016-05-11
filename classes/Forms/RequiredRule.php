<?php

/**
 * Rules stating a non-empty requirement
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw\Forms;

/**
 * Rules stating a non-empty requirement
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

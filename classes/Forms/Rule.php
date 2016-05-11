<?php

/**
 * Rules regarding validation
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw\Forms;

use Pfw\Lang;

/**
 * Rules regarding validation
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
     * @param Control   $control
     * @param Lang $lang
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

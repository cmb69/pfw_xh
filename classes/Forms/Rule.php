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

use XMLWriter;
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
     * @var Control $control
     */
    protected $control;

    /**
     * The language
     *
     * @var Lang $lang
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
     * @param XMLWriter $writer
     *
     * @return void
     */
    abstract public function render(XMLWriter $writer);

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
     * @param float     $value The actual value
     * @param XMLWriter $writer
     *
     * @return void
     */
    abstract public function renderValidationError($value, XMLWriter $writer);

    /**
     * Actually renders a validation error message
     *
     * @param string $text
     * @param XMLWriter $writer
     * @return void
     */
    protected function doRenderValidationError($text, XMLWriter $writer)
    {
        $writer->startElement('div');
        $writer->writeAttribute('class', 'pfw_validation_error');
        $writer->text($text);
        $writer->endElement();
    }
}

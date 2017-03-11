<?php

/*
Copyright 2016-2017 Christoph M. Becker
 
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
     * @param XMLWriter $writer
     *
     * @return void
     */
    public function render(XMLWriter $writer)
    {
        $writer->writeAttribute('pattern', $this->pattern);
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
     * @param float     $value The actual value
     * @param XMLWriter $writer
     *
     * @return void
     */
    public function renderValidationError($value, XMLWriter $writer)
    {
        $this->doRenderValidationError(
            $this->lang->singular('validation_pattern', $this->control->label(), $this->pattern),
            $writer
        );
    }
}

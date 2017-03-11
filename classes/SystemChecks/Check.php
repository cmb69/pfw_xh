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

namespace Pfw\SystemChecks;

use Pfw\Lang;

/**
 * The abstract base class of all checks
 */
abstract class Check
{
    /**
     * Whether this requirement is mandatory
     *
     * @var bool
     */
    private $isMandatory;

    /**
     * The language
     *
     * @var Lang $lang
     */
    protected $lang;

    /**
     * Constructs an instance
     *
     * @param bool $isMandatory
     */
    public function __construct($isMandatory)
    {
        $this->isMandatory = $isMandatory;
        $this->lang = \Pfw\System::getLang('pfw');
    }

    /**
     * Returns whether the check succeeded, i.e.\ the requirement is fulfilled
     *
     * @return bool
     */
    abstract protected function check();

    /**
     * Returns the textual representation of the requirement
     *
     * @return string
     */
    abstract public function getText();

    /**
     * Returns the status.
     *
     * @returns string ('success', 'warning', 'fail')
     */
    public function getStatus()
    {
        if ($this->check()) {
            return 'success';
        }
        if ($this->isMandatory) {
            return 'fail';
        }
        return 'warning';
    }
}

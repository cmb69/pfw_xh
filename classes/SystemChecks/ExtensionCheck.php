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

namespace Pfw\SystemChecks;

/**
 * Checks whether a certain PHP extension is loaded
 *
 * Note that this check might be too late, e.g. because a required
 * extension is not available.
 * Therefore it is recommended to also explicitly document
 * this requirement prominently.
 */
class ExtensionCheck extends Check
{
    /**
     * The name of the extension
     *
     * @var string
     */
    private $extensionName;

    /**
     * Constructs an instance
     *
     * @param bool   $isMandatory
     * @param string $extensionName
     */
    public function __construct($isMandatory, $extensionName)
    {
        parent::__construct($isMandatory);
        $this->extensionName = $extensionName;
    }

    /**
     * Returns whether the check succeeded, i.e. the requirement is fulfilled
     *
     * @return bool
     */
    protected function check()
    {
        return extension_loaded($this->extensionName);
    }

    /**
     * Returns the textual representation of the requirement
     *
     * @return string
     */
    protected function text()
    {
        return $this->lang->singular('syscheck_extension', $this->extensionName);
    }
}

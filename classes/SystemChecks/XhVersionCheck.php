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

/**
 * Checks for a minimum CMSimple_XH version
 *
 * The check is supposed to be foolproof with regard to early versions of
 * CMSimple 4, which also defined CMSIMPLE_XH_VERSION, albeit in an incompatible
 * way.
 */
class XhVersionCheck extends Check
{
    /**
     * The required CMSimple_XH version
     *
     * @var string
     */
    private $requiredVersion;

    /**
     * Constructs an instance
     *
     * @param bool   $isMandatory
     * @param string $requiredVersion
     */
    public function __construct($isMandatory, $requiredVersion)
    {
        parent::__construct($isMandatory);
        $this->requiredVersion = $requiredVersion;
    }

    /**
     * Returns whether the check succeeded, i.e.\ the requirement is fulfilled
     *
     * @return bool
     */
    protected function check()
    {
        return defined('CMSIMPLE_XH_VERSION')
            && strpos(CMSIMPLE_XH_VERSION, 'CMSimple_XH') === 0
            && version_compare(CMSIMPLE_XH_VERSION, "CMSimple_XH {$this->requiredVersion}", 'gt');
    }

    /**
     * Returns the textual representation of the requirement
     *
     * @return string
     */
    public function getText()
    {
        return $this->lang->singular('syscheck_xhversion', $this->requiredVersion);
    }
}

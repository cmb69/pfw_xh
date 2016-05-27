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
 * Checks whether magic_quotes_runtime is off
 *
 * We suppose that magic_quotes_runtime is off everywhere, but as it would
 * most likely cause heavy malfunctions, we check it nonetheless.
 */
class NoMagicQuotesCheck extends Check
{
    /**
     * Returns whether the check succeeded, i.e.\ the requirement is fulfilled
     *
     * @return bool
     */
    protected function check()
    {
        return !get_magic_quotes_runtime();
    }

    /**
     * Returns the textual representation of the requirement
     *
     * @return string
     */
    public function text()
    {
        return $this->lang->get('syscheck_magic_quotes');
    }
}

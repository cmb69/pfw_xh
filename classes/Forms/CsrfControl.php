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

/**
 * CSRF protection form controls
 */
class CsrfControl extends Control
{
    /**
     * Renders the control
     *
     * @param \SimpleXMLElement $form
     *
     * @return void
     */
    public function render(\SimpleXMLElement $form)
    {
        global $_XH_csrfProtection;

        $html = $_XH_csrfProtection->tokenInput();
        preg_match('/value="([a-z0-9]+)"/', $html, $matches);
        $input = $form->addChild('input');
        $input->addAttribute('type', 'hidden');
        $input->addAttribute('name', 'xh_csrf_token');
        $input->addAttribute('value', $matches[1]);
    }

    /**
     * Returns whether the current value of the control is valid
     */
    public function validate()
    {
        global $_XH_csrfProtection;

        $_XH_csrfProtection->check();
        return true;
    }
}

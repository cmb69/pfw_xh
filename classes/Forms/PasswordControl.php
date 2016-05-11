<?php

/**
 * Password input form controls
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw\Forms;

/**
 * Password input form controls
 */
class PasswordControl extends InputControl
{
    /**
     * Renders the type attribute of the input
     *
     * @param \SimpleXMLElement $sxe
     *
     * @return void
     */
    public function renderTypeAttribute(\SimpleXMLElement $sxe)
    {
        $sxe->addAttribute('type', 'password');
    }
}

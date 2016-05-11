<?php

/**
 * CSRF protection form controls
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
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

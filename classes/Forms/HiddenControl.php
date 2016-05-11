<?php

/**
 * Hidden input form controls
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw\Forms;

/**
 * Hidden input form controls
 */
class HiddenControl extends InputControl
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
        $sxe->addAttribute('type', 'hidden');
    }

    /**
     * Renders the control's label
     *
     * As hidden input controls don't need a label, we simply do nothing here.
     *
     * @param \SimpleXMLElement $form
     *
     * @return void
     */
    public function renderLabel(\SimpleXMLElement $form)
    {
        // do nothing
    }
}

<?php

/**
 * HTML views
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw;

/**
 * HTML views
 */
class HtmlView extends View
{
    /**
     * Returns a properly HTML escaped string.
     *
     * @param string $string
     *
     * @return string
     */
    protected function escape($string)
    {
        return htmlspecialchars($string, ENT_COMPAT, 'UTF-8');
    }
}

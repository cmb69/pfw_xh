<?php

/**
 * Checks whether magic_quotes_runtime is off
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
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
     * Returns whether the check succeeded, i.e. the requirement is fulfilled
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
    protected function text()
    {
        return $this->lang->get('syscheck_magic_quotes');
    }
}

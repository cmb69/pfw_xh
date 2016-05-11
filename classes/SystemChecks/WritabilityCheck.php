<?php

/**
 * Checks whether a file or folder is writable
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw\SystemChecks;

/**
 * Checks whether a file or folder is writable
 */
class WritabilityCheck extends Check
{
    /**
     * The filename
     *
     * @var string
     */
    private $filename;

    /**
     * Constructs an instance
     *
     * @param bool   $isMandatory
     * @param string $filename
     */
    public function __construct($isMandatory, $filename)
    {
        parent::__construct($isMandatory);
        $this->filename = $filename;
    }

    /**
     * Returns whether the check succeeded, i.e. the requirement is fulfilled
     *
     * @return bool
     */
    protected function check()
    {
        return is_writable($this->filename);
    }

    /**
     * Returns the textual representation of the requirement
     *
     * @return string
     */
    protected function text()
    {
        return $this->lang->singular('syscheck_writable', $this->filename);
    }
}

<?php

/**
 * Checks whether a certain PHP extension is loaded
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
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

<?php

/**
 * Checks for a minimum PHP version
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw\SystemChecks;

/**
 * Checks for a minimum PHP version
 *
 * Note that this check is often too late, e.g. because a non-supported
 * PHP version might already fail during parsing the code.
 * Therefore it is highly recommended to also explicitly document
 * this requirement prominently.
 */
class PhpVersionCheck extends Check
{
    /**
     * The required PHP version
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
     * Returns whether the check succeeded, i.e. the requirement is fulfilled
     *
     * @return bool
     */
    protected function check()
    {
        return version_compare(PHP_VERSION, $this->requiredVersion, 'ge');
    }

    /**
     * Returns the textual representation of the requirement
     *
     * @return string
     */
    protected function text()
    {
        return $this->lang->singular('syscheck_phpversion', $this->requiredVersion);
    }
}

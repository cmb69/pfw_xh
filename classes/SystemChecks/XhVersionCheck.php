<?php

/**
 * Checks for a minimum CMSimple_XH version
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw\SystemChecks;

/**
 * Checks for a minimum CMSimple_XH version
 *
 * The check is supposed to be foolproof with regard to early versions of
 * CMSimple 4, which also defined CMSIMPLE_XH_VERSION, albeit in an incompatible
 * way.
 */
class XhVersionCheck extends Check
{
    /**
     * The required CMSimple_XH version
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
        return defined('CMSIMPLE_XH_VERSION')
            && strpos(CMSIMPLE_XH_VERSION, 'CMSimple_XH') === 0
            && version_compare(CMSIMPLE_XH_VERSION, "CMSimple_XH {$this->requiredVersion}", 'gt');
    }

    /**
     * Returns the textual representation of the requirement
     *
     * @return string
     */
    protected function text()
    {
        return $this->lang->singular('syscheck_xhversion', $this->requiredVersion);
    }
}

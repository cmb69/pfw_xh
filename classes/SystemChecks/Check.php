<?php

/**
 * The abstract base class of all checks
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw\SystemChecks;

use Pfw\Lang;

/**
 * The abstract base class of all checks
 */
abstract class Check
{
    /**
     * Whether this requirement is mandatory
     *
     * @var bool
     */
    private $isMandatory;

    /**
     * The language
     *
     * @var Lang
     */
    protected $lang;

    /**
     * Constructs an instance
     *
     * @param bool $isMandatory
     */
    public function __construct($isMandatory)
    {
        $this->isMandatory = $isMandatory;
        $this->lang = Lang::instance('pfw');
    }

    /**
     * Renders the check
     *
     * @eturn string HTML
     */
    public function render()
    {
        return sprintf('<p>%s %s</p>', $this->renderStatus(), $this->text());
    }

    /**
     * Returns whether the check succeeded, i.e. the requirement is fulfilled
     *
     * @return bool
     */
    abstract protected function check();

    /**
     * Returns the textual representation of the requirement
     *
     * @return string
     */
    abstract protected function text();

    /**
     * Renders the appropriate status icon
     *
     * @return string
     */
    private function renderStatus()
    {
        global $pth;

        $status = $this->status();
        $src = "{$pth['folder']['base']}core/css/$status.png";
        $alt = $this->lang->get("syscheck_alt_$status");
        return tag(sprintf('img src="%s" alt="%s"', $src, $alt));
    }

    /**
     * Returns the status ('success', 'warning', 'failure')
     *
     * @returns string
     */
    private function status()
    {
        if ($this->check()) {
            return 'success';
        }
        if ($this->isMandatory) {
            return 'failure';
        }
        return 'warning';
    }
}

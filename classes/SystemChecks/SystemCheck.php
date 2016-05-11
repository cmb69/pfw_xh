<?php

/**
 * System checks
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw\SystemChecks;

/**
 * System checks
 *
 * System checks are supposed to check for any requirements a plugin might
 * have. The actual checks are done by Check and its subclasses, but the
 * SystemChecks are the preferred way to build the collection of Checks.
 *
 * Example:
 *
 *      $systemCheck = new SystemCheck();
 *      echo $systemCheck
 *          ->mandatory()
 *              ->phpVersion('5.3')
 *          ->optional()
 *              ->extension('gd')
 *          ->mandatory()
 *              ->noMagicQuotes()
 *              ->xhVersion('1.6')
 *          ->optional()
 *              ->writable($filename)
 *          ->render();
 *
 * @todo Add check for a plugin which has to be installed (jQuery etc.).
 *       It might be useful to also check for the plugin's version.
 */
class SystemCheck
{
    /**
     * Whether the following checks are mandatory
     *
     * @var bool
     */
    private $isMandatory;

    /**
     * The list of checks
     *
     * @var array<Check>
     */
    private $checks;

    /**
     * Marks the following checks as mandatory
     *
     * @return self
     */
    public function mandatory()
    {
        $this->isMandatory = true;
        return $this;
    }

    /**
     * Marks the following checks as optional
     *
     * @return self
     */
    public function optional()
    {
        $this->isMandatory = false;
        return $this;
    }

    /**
     * Checks for a minimum PHP version
     *
     * @param string $requiredVersion
     *
     * @return self
     */
    public function phpVersion($requiredVersion)
    {
        $this->addCheck(
            new PHPVersionCheck($this->isMandatory, $requiredVersion)
        );
        return $this;
    }

    /**
     * Checks for a PHP extension
     *
     * @param string $name
     *
     * @return self
     */
    public function extension($name)
    {
        $this->addCheck(
            new ExtensionCheck($this->isMandatory, $name)
        );
        return $this;
    }

    /**
     * Checks that magic_quotes_runtime is off
     *
     * @return self
     */
    public function noMagicQuotes()
    {
        $this->addCheck(
            new NoMagicQuotesCheck($this->isMandatory)
        );
        return $this;
    }

    /**
     * Checks for a minimum CMSimple_XH version
     *
     * @param string $requiredVersion
     *
     * @return self
     */
    public function xhVersion($requiredVersion)
    {
        $this->addCheck(
            new XhVersionCheck($this->isMandatory, $requiredVersion)
        );
        return $this;
    }

    /**
     * Checks whether a file or folder is writable
     *
     * @param string $filename
     *
     * @return self
     */
    public function writable($filename)
    {
        $this->addCheck(
            new WritabilityCheck($this->isMandatory, $filename)
        );
        return $this;
    }

    /**
     * Adds a check
     *
     * @param Check $check
     *
     * @return void
     */
    private function addCheck(Check $check)
    {
        $this->checks[] = $check;
    }

    /**
     * Renders the result of the checks
     *
     * @return string HTML
     */
    public function render()
    {
        $html = array();
        foreach ($this->checks as $check) {
            $html[] = $check->render();
        }
        return implode("\n", $html);
    }
}

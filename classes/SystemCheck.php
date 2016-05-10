<?php

/**
 * System checks
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw;

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

/**
 * The abstract base class for all checks.
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

<?php

/**
 * The plugin framework
 */
namespace Pfw;

/**
 * System checks
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
 * @todo add check for a plugin which has to be installed (jQuery etc.)
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
 * @internal
 */
abstract class Check
{
    private $isMandatory;

    protected $lang;

    public function __construct($isMandatory)
    {
        $this->isMandatory = $isMandatory;
        $this->lang = Lang::instance('pfw');
    }

    /**
     * @eturn string HTML
     */
    public function render()
    {
        return sprintf('<p>%s %s</p>', $this->renderStatus(), $this->text());
    }

    /**
     * @return int
     */
    abstract protected function check();

    abstract protected function text();

    private function renderStatus()
    {
        global $pth;

        $status = $this->status();
        $src = "{$pth['folder']['base']}core/css/$status.png";
        $alt = $this->lang->get("syscheck_alt_$status");
        return tag(sprintf('img src="%s" alt="%s"', $src, $alt));
    }

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
 * @internal
 */
class PhpVersionCheck extends Check
{
    private $requiredVersion;

    public function __construct($isMandatory, $requiredVersion)
    {
        parent::__construct($isMandatory);
        $this->requiredVersion = $requiredVersion;
    }

    protected function check()
    {
        return version_compare(PHP_VERSION, $this->requiredVersion, 'ge');
    }

    protected function text()
    {
        return $this->lang->singular('syscheck_phpversion', $this->requiredVersion);
    }
}

/**
 * @internal
 */
class ExtensionCheck extends Check
{
    private $extensionName;
    
    public function __construct($isMandatory, $extensionName)
    {
        parent::__construct($isMandatory);
        $this->extensionName = $extensionName;
    }
    
    protected function check()
    {
        return extension_loaded($this->extensionName);
    }

    protected function text()
    {
        return $this->lang->singular('syscheck_extension', $this->extensionName);
    }
}

/**
 * @internal
 */
class NoMagicQuotesCheck extends Check
{
    protected function check()
    {
        return !get_magic_quotes_runtime();
    }
    
    protected function text()
    {
        return $this->lang->get('syscheck_magic_quotes');
    }
}

/**
 * @internal
 */
class XhVersionCheck extends Check
{
    private $requiredVersion;

    public function __construct($isMandatory, $requiredVersion)
    {
        parent::__construct($isMandatory);
        $this->requiredVersion = $requiredVersion;
    }

    protected function check()
    {
        return defined('CMSIMPLE_XH_VERSION')
            && strpos(CMSIMPLE_XH_VERSION, 'CMSimple_XH') === 0
            && version_compare(CMSIMPLE_XH_VERSION, "CMSimple_XH {$this->requiredVersion}", 'gt');
    }

    protected function text()
    {
        return $this->lang->singular('syscheck_xhversion', $this->requiredVersion);
    }
}

/**
 * @internal
 */
class WritabilityCheck extends Check
{
    private $filename;

    public function __construct($isMandatory, $filename)
    {
        parent::__construct($isMandatory);
        $this->filename = $filename;
    }

    protected function check()
    {
        return is_writable($this->filename);
    }

    protected function text()
    {
        return $this->lang->singular('syscheck_writable', $this->filename);
    }
}

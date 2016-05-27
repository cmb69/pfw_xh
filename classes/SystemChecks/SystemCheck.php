<?php

/*
Copyright 2016 Christoph M. Becker
 
This file is part of Pfw_XH.

Pfw_XH is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Pfw_XH is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Pfw_XH.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace Pfw\SystemChecks;

/**
 * %System checks
 *
 * %System checks are supposed to check for any requirements a plugin might
 * have. The actual checks are done by Check and its subclasses, but the
 * SystemCheck is the preferred way to build the collection of Checks.
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
     * @var Check[]
     */
    private $checks;

    /**
     * Marks the following checks as mandatory
     *
     * @return $this
     */
    public function mandatory()
    {
        $this->isMandatory = true;
        return $this;
    }

    /**
     * Marks the following checks as optional
     *
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * Returns all checks.
     *
     * @return Check[]
     */
    public function checks()
    {
        return $this->checks;
    }
}

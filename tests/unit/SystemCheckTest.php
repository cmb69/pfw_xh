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

namespace Pfw;

use org\bovigo\vfs\vfsStream;
use Pfw\SystemChecks\SystemCheck;

class SystemCheckTest extends \PHPUnit_Framework_TestCase
{
    private $root;

    public function setUp()
    {
        global $plugin_tx;

        define('CMSIMPLE_XH_VERSION', 'CMSimple_XH 1.6.7');
        $plugin_tx = array(
            'pfw' => array(
                'syscheck_phpversion' => 'PHP Version &ge; %s',
                'syscheck_extension' => 'PHP extension %s loaded',
                'syscheck_magic_quotes' => 'Magic quotes runtime off',
                'syscheck_xhversion' => 'CMSimple_XH version &ge; %s',
                'syscheck_writable' => '%s is writable',
                'syscheck_alt_success' => 'Success',
                'syscheck_alt_warning' => 'Warning',
                'syscheck_alt_failure' => 'Failure',
            )
        );
        $this->root = vfsStream::setup();
    }

    public function testIt()
    {
        $subject = new SystemCheck();
        $subject
            ->mandatory()
                ->phpVersion('15.3')
            ->optional()
                ->extension('foo')
            ->mandatory()
                ->noMagicQuotes()
                ->xhVersion('1.6')
            ->optional()
                ->writable($this->root->url());
        $this->assertEquals(
            '<p><img src="core/css/failure.png" alt="Failure"> PHP Version &ge; 15.3</p>
<p><img src="core/css/warning.png" alt="Warning"> PHP extension foo loaded</p>
<p><img src="core/css/success.png" alt="Success"> Magic quotes runtime off</p>
<p><img src="core/css/success.png" alt="Success"> CMSimple_XH version &ge; 1.6</p>
<p><img src="core/css/success.png" alt="Success"> vfs://root is writable</p>',
            $subject->render()
        );
    }
}

function tag($html)
{
    return "<$html>";
}

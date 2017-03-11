<?php

/*
Copyright 2016-2017 Christoph M. Becker
 
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

class SystemCheckTest extends TestCase
{
    private $root;
    
    private $subject;

    public function setUp()
    {
        parent::setUp();
        $this->defineConstant('CMSIMPLE_XH_VERSION', 'CMSimple_XH 1.6.7');
        $this->root = vfsStream::setup();
        $this->subject = new SystemCheck();
   }

    public function testPhpVersion()
    {
        $this->subject->mandatory()->phpVersion('12345');
        $this->assertEquals('failure', $this->firstCheck()->status());
        System::lang('pfw')->expects($this->once())->method('singular')
            ->with('syscheck_phpversion', '12345');
        $this->firstCheck()->text();
    }
    
    public function testExtension()
    {
        $this->subject->optional()->extension('foo');
        $this->assertEquals('warning', $this->firstCheck()->status());
        System::lang('pfw')->expects($this->once())->method('singular')
            ->with('syscheck_extension', 'foo');
        $this->firstCheck()->text();
    }
    
    public function testXhVersion()
    {
        $this->subject->mandatory()->xhVersion('1.6');
        $this->assertEquals('success', $this->firstCheck()->status());
        System::lang('pfw')->expects($this->once())->method('singular')
            ->with('syscheck_xhversion', '1.6');
        $this->firstCheck()->text();
    }
    
    public function testWritable()
    {
        $this->subject->optional()->writable($this->root->url());
        $this->assertEquals('success', $this->firstCheck()->status());
        System::lang('pfw')->expects($this->once())->method('singular')
            ->with('syscheck_writable', $this->root->url());
        $this->firstCheck()->text();
    }
    
    private function firstCheck()
    {
        return $this->subject->checks()[0];
    }
}

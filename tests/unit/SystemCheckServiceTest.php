<?php

/*
 * Copyright 2017 Christoph M. Becker
 *
 * This file is part of Pfw_XH.
 *
 * Pfw_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Pfw_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Pfw_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Pfw;

use Pfw\TestCase;

class SystemCheckServiceTest extends TestCase
{
    /**
     * @return void
     */
    public function testChecksAreEmpty()
    {
        $subject = new SystemCheckService();

        $this->assertEmpty($subject->getChecks());
    }

    /**
     * @return void
     * @covers Pfw\SystemCheckService::minPhpVersion
     */
    public function testMinPhpVersion()
    {
        $subject = new SystemCheckService;

        $subject->minPhpVersion('5.0.0');

        $checks = $subject->getChecks();
        $this->assertContainsOnlyInstancesOf(SystemCheck::class, $checks);
        $this->assertCount(1, $checks);
        $this->assertEquals(SystemCheck::SUCCESS, $checks[0]->getState());
    }

    /**
     * @return void
     * @covers Pfw\SystemCheckService::extension
     */
    public function testExtension()
    {
        $subject = new SystemCheckService;

        $subject->extension('standard');

        $checks = $subject->getChecks();
        $this->assertContainsOnlyInstancesOf(SystemCheck::class, $checks);
        $this->assertCount(1, $checks);
        $this->assertEquals(SystemCheck::SUCCESS, $checks[0]->getState());
    }

    /**
     * @return void
     * @covers Pfw\SystemCheckService::minXhVersion
     */
    public function testMinXhVersion()
    {
        $this->setConstant('CMSIMPLE_XH_VERSION', 'CMSimple_XH 1.7.0');
        $subject = new SystemCheckService;

        $subject->minXhVersion('1.6.3');

        $checks = $subject->getChecks();
        $this->assertContainsOnlyInstancesOf(SystemCheck::class, $checks);
        $this->assertCount(1, $checks);
        $this->assertEquals(SystemCheck::SUCCESS, $checks[0]->getState());
    }

    /**
     * @return void
     * @covers Pfw\SystemCheckService::plugin
     */
    public function testPlugin()
    {
        $isdirmock = $this->mockFunction('is_dir');
        $isdirmock->expects($this->any())->willReturn(true);
        $subject = new SystemCheckService;
        
        $subject->plugin('pagemanager');

        $checks = $subject->getChecks();
        $this->assertContainsOnlyInstancesOf(SystemCheck::class, $checks);
        $this->assertCount(1, $checks);
        $this->assertEquals(SystemCheck::SUCCESS, $checks[0]->getState());

        $isdirmock->restore();
    }

    /**
     * @return void
     * @covers Pfw\SystemCheckService::writable
     */
    public function testWritable()
    {
        $iswritablemock = $this->mockFunction('is_writable');
        $iswritablemock->expects($this->any())->willReturn(true);
        $subject = new SystemCheckService;

        $subject->writable('foo/bar/baz');

        $checks = $subject->getChecks();
        $this->assertContainsOnlyInstancesOf(SystemCheck::class, $checks);
        $this->assertCount(1, $checks);
        $this->assertEquals(SystemCheck::SUCCESS, $checks[0]->getState());

        $iswritablemock->restore();
    }
}

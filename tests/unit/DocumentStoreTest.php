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
use Pfw\DataSource\DocumentStore;
use Pfw\DataSource\Document;

class DocumentStoreTest extends TestCase
{
    const BASENAME = 'foo.txt';

    const CONTENTS = 'some text';

    private $root;

    private $subject;

    private $filename;

    public function setUp()
    {
        parent::setUp();
        $this->root = vfsStream::setup();
        $this->subject = new DocumentStore($this->root->url());
        $this->filename = $this->root->url() . '/' . self::BASENAME;
    }

    public function testInsert()
    {
        $document = new Document(self::CONTENTS);
        $this->assertTrue($this->subject->insert(self::BASENAME, $document));

        $this->assertFileExists($this->filename);
    }

    public function testCanNotInsertExisting()
    {
        file_put_contents($this->filename, self::CONTENTS);
        $document = new Document('another text');
        $this->assertFalse(
            @$this->subject->insert(self::BASENAME, $document)
        );
        $this->assertEquals(self::CONTENTS, file_get_contents($this->filename));
    }

    public function testExists()
    {
        file_put_contents($this->filename, self::CONTENTS);
        $this->assertTrue(
            $this->subject->exists(self::BASENAME)
        );
    }

    public function testDoesNotExist()
    {
        $this->assertFalse(
            $this->subject->exists(self::BASENAME)
        );
    }

    public function testNames()
    {
        file_put_contents($this->filename, self::CONTENTS);
        $this->assertEquals(
            array(self::BASENAME),
            $this->subject->names()
        );
    }

    public function testFind()
    {
        file_put_contents($this->filename, self::CONTENTS);
        $document = $this->subject->find(self::BASENAME);
        $this->assertEquals(self::CONTENTS, $document->contents());
    }

    public function testFindNonExisting()
    {
        $this->assertFalse(
            @$this->subject->find(self::BASENAME)
        );
    }

    public function testUpdate()
    {
        file_put_contents($this->filename, self::CONTENTS);
        $document = $this->subject->find(self::BASENAME);
        $document = new Document('another text', $document->token());
        $this->assertTrue(
            $this->subject->update(self::BASENAME, $document)
        );
        $this->assertEquals('another text', file_get_contents($this->filename));
    }

    public function testCantUpdateNonExisting()
    {
        $document = new Document(self::CONTENTS);
        $this->assertFalse(
            @$this->subject->update(self::BASENAME, $document)
        );
    }

    public function testUpdateFailsDueToOfflineConcurrency()
    {
        file_put_contents($this->filename, self::CONTENTS);
        $document = $this->subject->find(self::BASENAME);
        file_put_contents($this->filename, 'another text');
        $this->assertFalse(
            $this->subject->update(self::BASENAME, $document)
        );
        $this->assertEquals('another text', file_get_contents($this->filename));
    }

    public function testDelete()
    {
        file_put_contents($this->filename, self::CONTENTS);
        $document = $this->subject->find(self::BASENAME);
        $this->assertTrue(
            $this->subject->delete(self::BASENAME, $document)
        );
        $this->assertFileNotExists($this->filename);
    }

    public function testCantDeleteNonExisting()
    {
        $document = new Document(self::CONTENTS);
        $this->assertFalse(
            @$this->subject->delete(self::BASENAME, $document)
        );
    }

    public function testDeleteFailsDueToOfflineConcurrency()
    {
        file_put_contents($this->filename, self::CONTENTS);
        $document = $this->subject->find(self::BASENAME);
        file_put_contents($this->filename, 'another text');
        $this->assertFalse(
            $this->subject->delete(self::BASENAME, $document)
        );
        $this->assertFileExists($this->filename);
    }
}

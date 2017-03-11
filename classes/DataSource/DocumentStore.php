<?php

/*
 * Copyright 2016-2017 Christoph M. Becker
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

namespace Pfw\DataSource;

/**
 * Document stores
 *
 * Documents are stored as files with arbitrary content.
 * Pessimistic online concurrency is automatically handled via file locking.
 * Optimistic offline concurrency is handled via tokens.
 */
class DocumentStore
{
    /**
     * @var string
     */
    private $folder;

    /**
     * Constructs an instance.
     *
     * @param string $folder
     */
    public function __construct($folder)
    {
        $this->folder = $folder;
    }

    /**
     * Returns the basenames of all existing documents.
     *
     * @return string[]
     */
    public function getNames()
    {
        $names = [];
        foreach (scandir($this->folder) as $basename) {
            if (strpos($basename, '.') !== 0) {
                $names[] = $basename;
            }
        }
        return $names;
    }

    /**
     * Returns whether a document exists.
     *
     * @param string $basename
     *
     * @return bool
     */
    public function exists($basename)
    {
        return file_exists($this->getFilenameOf($basename));
    }

    /**
     * Inserts a new document and returns whether that succeeded.
     *
     * @param string   $basename
     * @param Document $document
     *
     * @return bool
     */
    public function insert($basename, Document $document)
    {
        $filename = $this->getFilenameOf($basename);
        $stream = fopen($filename, 'x');
        if (!$stream) {
            return false;
        }
        flock($stream, LOCK_EX);
        fwrite($stream, $document->getContents());
        flock($stream, LOCK_UN);
        fclose($stream);
        return true;
    }

    /**
     * Returns a document from the store, or false on failure.
     *
     * @param string $basename
     *
     * @return Document
     */
    public function find($basename)
    {
        $stream = fopen($this->getFilenameOf($basename), 'r');
        if (!$stream) {
            return false;
        }
        flock($stream, LOCK_SH);
        $contents = stream_get_contents($stream);
        flock($stream, LOCK_UN);
        fclose($stream);
        $token = md5($contents);
        return new Document($contents, $token);
    }

    /**
     * Updates a document and returns whether that succeeded.
     *
     * @param string   $basename
     * @param Document $document
     *
     * @return bool
     */
    public function update($basename, Document $document)
    {
        $stream = fopen($this->getFilenameOf($basename), 'r+');
        if (!$stream) {
            return false;
        }
        flock($stream, LOCK_EX);
        $contents = stream_get_contents($stream);
        $token = md5($contents);
        if ($token == $document->getToken()) {
            rewind($stream);
            fwrite($stream, $document->getContents());
            ftruncate($stream, ftell($stream));
        }
        flock($stream, LOCK_UN);
        fclose($stream);
        return $token == $document->getToken();
    }

    /**
     * Deletes a document and returns whether that succeeded.
     *
     * @param string   $basename
     * @param Document $document
     *
     * @return bool
     */
    public function delete($basename, Document $document)
    {
        $stream = fopen($this->getFilenameOf($basename), 'r+');
        if (!$stream) {
            return false;
        }
        flock($stream, LOCK_EX);
        $contents = stream_get_contents($stream);
        flock($stream, LOCK_UN);
        fclose($stream);
        $token = md5($contents);
        if ($token == $document->getToken()) {
            return unlink($this->getFilenameOf($basename));
        }
        return false;
    }

    /**
     * @param string $basename
     * @return string
     */
    private function getFilenameOf($basename)
    {
        return $this->folder . DIRECTORY_SEPARATOR . $basename;
    }
}

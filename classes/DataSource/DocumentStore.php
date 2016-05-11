<?php

/**
 * Document stores
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw\DataSource;

/**
 * Document stores
 *
 * {@see Document Documents} are stored as files with arbitrary content.
 * Pessimistic online concurrency is automatically handled via file locking.
 * Optimistic offline concurrency is handled via tokens.
 *
 * @see \Pfw\Document
 */
class DocumentStore
{
    /**
     * The folder
     *
     * @var string
     */
    private $folder;

    /**
     * Constructor
     *
     * @param string $folder
     */
    public function __construct($folder)
    {
        $this->folder = $folder;
    }

    /**
     * Returns the basenames of all existing documents
     *
     * @return array<string>
     */
    public function names()
    {
        $names = array();
        foreach (scandir($this->folder) as $basename) {
            if (strpos($basename, '.') !== 0) {
                $names[] = $basename;
            }
        }
        return $names;
    }

    /**
     * Returns whether a document exists
     *
     * @param string $basename
     *
     * @return bool
     */
    public function exists($basename)
    {
        return file_exists($this->filenameOf($basename));
    }

    /**
     * Inserts a new document
     *
     * @param string   $basename
     * @param Document $document
     *
     * @return bool Whether that succeed
     */
    public function insert($basename, Document $document)
    {
        $filename = $this->filenameOf($basename);
        $stream = fopen($filename, 'x');
        if (!$stream) {
            return false;
        }
        flock($stream, LOCK_EX);
        fwrite($stream, $document->contents());
        flock($stream, LOCK_UN);
        fclose($stream);
        return true;
    }

    /**
     * Finds a document
     *
     * @param string $basename
     *
     * @return Document|false The found document; false on failure
     */
    public function find($basename)
    {
        $stream = fopen($this->filenameOf($basename), 'r');
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
     * Updates a document
     *
     * @param string   $basename
     * @param Document $document
     *
     * @return bool Whether that succeed
     */
    public function update($basename, Document $document)
    {
        $stream = fopen($this->filenameOf($basename), 'r+');
        if (!$stream) {
            return false;
        }
        flock($stream, LOCK_EX);
        $contents = stream_get_contents($stream);
        $token = md5($contents);
        if ($token == $document->token()) {
            rewind($stream);
            fwrite($stream, $document->contents());
            ftruncate($stream, ftell($stream));
        }
        flock($stream, LOCK_UN);
        fclose($stream);
        return $token == $document->token();
    }

    /**
     * Deletes a document
     *
     * @param string   $basename
     * @param Document $document
     *
     * @return bool Whether that succeed
     */
    public function delete($basename, Document $document)
    {
        $stream = fopen($this->filenameOf($basename), 'r+');
        if (!$stream) {
            return false;
        }
        flock($stream, LOCK_EX);
        $contents = stream_get_contents($stream);
        flock($stream, LOCK_UN);
        fclose($stream);
        $token = md5($contents);
        if ($token == $document->token()) {
            return unlink($this->filenameOf($basename));
        }
        return false;
    }

    /**
     * Returns the filename
     *
     * @param string $basename
     *
     * @return string
     */
    private function filenameOf($basename)
    {
        return $this->folder . DIRECTORY_SEPARATOR . $basename;
    }
}

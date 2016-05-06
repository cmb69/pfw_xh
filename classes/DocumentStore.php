<?php

/**
 * The plugin framework
 */
namespace Pfw;

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

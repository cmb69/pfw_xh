<?php

/**
 * Documents for the DocumentStores
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw;

/**
 * Documents for the DocumentStores
 *
 * A document carries some arbitrary text contents and a token.
 * The contents is what is read from and written to the file.
 * The token is some opaque value specifying the version of the file contents.
 *
 * @see \Pfw\DocumentStore
 */
class Document
{
    /**
     * The contents
     *
     * @var string
     */
    private $contents;

    /**
     * The token
     *
     * @var mixed
     */
    private $token;

    /**
     * Constructor
     *
     * @param string $contents
     * @param mixed  $token
     */
    public function __construct($contents, $token = null)
    {
        $this->contents = $contents;
        $this->token = $token;
    }

    /**
     * Returns the token
     *
     * @return mixed
     */
    public function token()
    {
        return $this->token;
    }

    /**
     * Returns the contents
     *
     * @return string
     */
    public function contents()
    {
        return $this->contents;
    }
}

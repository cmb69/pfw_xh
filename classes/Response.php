<?php

/**
 * The plugin framework.
 */
namespace Pfw;

/**
 * Encapsulates the current HTTP response.
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */
class Response implements Singleton
{
    /**
     * Returns the single instance.
     *
     * @return self
     */
    public static function instance()
    {
        static $instance;

        if (!isset($instance)) {
            $instance = new self();
        }
        return $instance;
    }

    /**
     * Constructs a new instance.
     */
    private function __construct()
    {
    }

    /**
     * Appends HTML to the contents area.
     *
     * @param  string $html
     * @return void
     */
    public function append($html)
    {
        global $o;

        $o .= $html;
    }

    /**
     * Appends HTML to the head.
     *
     * @param  string $html
     * @return void
     */
    public function appendToHead($html)
    {
        global $hjs;

        $hjs .= $html;
    }

    /**
     * Appends HTML to the body.
     *
     * @param  string $html
     * @return void
     */
    public function appendToBody($html)
    {
        global $bjs;

        $bjs .= $html;
    }

    /**
     * Adds a styleheet link.
     *
     * @param  string $path
     * @return void
     */
    public function addStylesheet($path)
    {
        $html = sprintf('<link rel="stylesheet" type="text/css" href="%s">', $path);
        $this->appendToHead($html);
    }

    /**
     * Adds a script.
     *
     * @param  string $path
     * @return void
     */
    public function addScript($path)
    {
        $html = sprintf('<script type="text/javascript" src="%s"', $path);
        $this->appendToBody($html);
    }

    /**
     * Sets the document's title.
     *
     * @param  string $value
     * @return void
     */
    public function setTitle($value)
    {
        global $title;

        $title = $value;
    }
}

<?php

/**
 * The current HTTP response.
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw;

/**
 * Singletons
 *
 * @link      https://en.wikipedia.org/wiki/Singleton_pattern
 */
interface Singleton
{
    /**
     * Returns the single instance
     *
     * @return self
     */
    public static function instance();
}

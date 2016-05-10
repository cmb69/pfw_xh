<?php

/**
 * Admin controllers
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw;

/**
 * Admin controllers
 */
abstract class AdminController extends Controller
{
    /**
     * Returns an URL for the given action
     *
     * @param string $action
     *
     * @return Url
     */
    public function url($action)
    {
        return $this->request->url()->with('action', "plugin_$action");
    }
}

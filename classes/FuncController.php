<?php

/**
 * Function controllers
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw;

/**
 * Function controllers
 */
abstract class FuncController extends Controller
{
    /**
     * The name of the action parameter
     *
     * @var string
     */
    private $actionParam;

    /**
     * Constructs an instance
     *
     * @param Plugin $plugin
     * @param string $actionParam
     */
    public function __construct(Plugin $plugin, $actionParam)
    {
        parent::__construct($plugin);
        $this->actionParam = $actionParam;
    }

    /**
     * Returns an URL for the given action
     *
     * @param string $action
     *
     * @return Url
     */
    public function url($action)
    {
        return $this->request->url()->with($this->actionParam, $action);
    }
}

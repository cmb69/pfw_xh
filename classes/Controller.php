<?php

/**
 * Controllers
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw;

/**
 * Controllers
 *
 * All real functionality of plugins using the plugin framework is supposed
 * to be initiated in controllers, or more specificially in one of the
 * actions implemented by a controller. The routing, i.e. finding out
 * which action of which controller to call, is done by the {@see Plugin}.
 */
abstract class Controller
{
    /**
     * The plugin
     *
     * @var Plugin
     */
    protected $plugin;

    /**
     * The current request
     *
     * @var Request
     */
    protected $request;

    /**
     * The current response
     *
     * @var Response
     */
    protected $response;

    /**
     * The configuration
     *
     * @var Config
     */
    protected $config;

    /**
     * The language
     *
     * @var Lang
     */
    protected $lang;

    /**
     * Constructs a controller
     *
     * Controllers are supposed to be created automatically by the plugin,
     * so don't create controllers on your own.
     *
     * @param Plugin $plugin
     */
    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
        $this->request = Request::instance();
        $this->response = Response::instance();
        $this->config = $plugin->config();
        $this->lang = $plugin->lang();
    }

    /**
     * Returns the plugin
     *
     * @return Plugin
     */
    public function plugin()
    {
        return $this->plugin;
    }

    /**
     * Returns the path of CMSimple_XH's general content folder
     *
     * Note, that this is the content folder of the current language.
     *
     * @return string
     */
    public function contentFolder()
    {
        global $pth;

        return $pth['folder']['content'];
    }

    /**
     * Returns a configuration option
     *
     * @param string $key
     *
     * @return string
     */
    protected function config($key)
    {
        return $this->config->get($key);
    }

    /**
     * Creates a view associated to a certain template
     *
     * This is the preferred way to create a view; don't create views
     * directly (i.e. via new View).
     *
     * @param string $template
     *
     * @return View
     */
    protected function view($template)
    {
        return new View($this, $template);
    }

    /**
     * Creates an HTML view associated to a certain template
     *
     * This is the preferred way to create a view; don't create views
     * directly (i.e. via new View).
     *
     * @param string $template
     *
     * @return View
     */
    protected function htmlView($template)
    {
        return new HtmlView($this, $template);
    }

    /**
     * Creates a form builder
     *
     * This is the preferred way to create a form builder.
     *
     * @param string $action
     *
     * @return FormBuilder
     */
    protected function formBuilder($action)
    {
        return new FormBuilder($this->plugin->name(), $this->lang, $action);
    }

    /**
     * Triggers an immediate 303 See Other redirect
     *
     * Actually, of course only the respective header is sent to the user agent,
     * which is supposed to relocate to the new URL. In any way, the script is
     * terminated, and this function will not return.     *
     *
     * @param Url $url
     *
     * @return void
     */
    public function seeOther(Url $url)
    {
        $this->response->redirect($url->absolute(), 303);
    }

    /**
     * Returns an URL for the given action
     *
     * Actually, the current URL is modified so that it points to the given
     * action of this controller. All other query paramaters are kept as they
     * are.
     *
     * @param string $action
     *
     * @return Url
     */
    abstract function url($action);
}

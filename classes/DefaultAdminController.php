<?php

/**
 * Default admin controllers
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw;

/**
 * Default admin controllers
 *
 * The default admin controller handles the plugin administration
 * when there is no `admin` parameter given. This is typically the case
 * when the webmaster selects the plugin in the plugin menu directly
 * (i.e. not any of its submenu entries).
 *
 * This controller is supposed to be extended in other plugins,
 * if so desired.
 */
class DefaultAdminController extends Controller
{
    /**
     * Handles the default action
     *
     * Displays some information about the plugin.
     *
     * @return void
     */
    public function indexAction()
    {
        $view = $this->htmlView('info');
        $view->title = ucfirst($this->plugin->name);
        $view->logo = $this->plugin->folder . $this->plugin->name . '.png';
        $view->version = $this->plugin->version;
        $view->copyright = $this->plugin->copyright;
        $view->systemCheck = $this->systemCheck();
        $view->render();
    }

    /**
     * Returns the system check, ready to be rendered
     *
     * Supposed to be overriden in other plugins, if these have more demanding
     * or further requirements than the plugin framework itself.
     *
     * @return SystemCheck
     */
    protected function systemCheck()
    {
        $systemCheck = new SystemChecks\SystemCheck();
        return $systemCheck
            ->mandatory()
                ->phpVersion('5.3')
                ->extension('SimpleXML')
                ->noMagicQuotes()
                ->xhVersion('1.6');
    }
}

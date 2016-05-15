<?php

/**
 * Language admin controllers
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw;

/**
 * Language admin controllers
 *
 * Adapters for the customary plugin loader facility
 * to let the webmaster edit the plugin language in the back-end.
 */
class LanguageAdminController extends Controller
{
    /**
     * Returns an appropriate file edit object
     *
     * @return \XH_PluginConfigFileEdit
     */
    protected function createFileEdit()
    {
        global $pth;

        include_once "{$pth['folder']['classes']}FileEdit.php";
        return new \XH_PluginLanguageFileEdit();
    }
    
    /**
     * Returns the dispatcher
     */
    public function getDispatcher()
    {
        return 'action';
    }

// @codingStandardsIgnoreStart
    /**
     * The plugin_edit action
     *
     * @return void
     */
    public function plugin_editAction()
    {
// @codingStandardsIgnoreEnd
        $url = $this->url('plugin_save');
        echo preg_replace(
            '/<form([^>]+)action="([^"]*)"/',
            "<form$1action=\"$url\"",
            $this->createFileEdit()->form()
        );
    }

// @codingStandardsIgnoreStart
    /**
     * The plugin_save
     *
     * @return void
     */
    public function plugin_saveAction()
    {
// @codingStandardsIgnoreEnd
        echo $this->createFileEdit()->submit();
    }
}

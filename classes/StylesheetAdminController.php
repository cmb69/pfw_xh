<?php

/**
 * Stylesheet admin controllers
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw;

/**
 * Stylesheet admin controllers
 *
 * Adapters for the customary plugin loader facility
 * to let the webmaster edit the plugin stylesheet in the back-end.
 */
class StylesheetAdminController extends Controller
{
    /**
     * The file edit object
     *
     * @var \XH_PluginTextFileEdit
     */
    private $fileEdit;

    /**
     * Constructs an instance
     *
     * @param Plugin $plugin
     */
    public function __construct(Plugin $plugin)
    {
        global $pth;

        parent::__construct($plugin);
        include_once "{$pth['folder']['classes']}FileEdit.php";
        $this->fileEdit = new \XH_PluginTextFileEdit();
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
     * The plugin_text action
     *
     * @return void
     */
    public function plugin_textAction()
    {
// @codingStandardsIgnoreEnd
        $url = $this->url('plugin_textsave');
        echo preg_replace(
            '/<form([^>]+)action="([^"]*)"/',
            "<form$1action=\"$url\"",
            $this->fileEdit->form()
        );
    }

// @codingStandardsIgnoreStart
    /**
     * The plugin_textsave action
     *
     * @return void
     */
    public function plugin_textsaveAction()
    {
// @codingStandardsIgnoreEnd
        echo $this->fileEdit->submit();
    }
}

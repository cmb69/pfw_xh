<?php

/**
 * Configuration admin controllers
 *
 * @copyright 2016 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GPLv3
 */

namespace Pfw;

/**
 * Configuration admin controllers
 *
 * This is just a small wrapper over the customary plugin loader facility
 * to let the webmaster edit the plugin stylesheet in the back-end.
 */
class ConfigAdminController extends AdminController
{
    /**
     * The file edit object
     *
     * @var \XH_PluginConfigFileEdit
     */
    private $fileEdit;

    /**
     * Constructs an instance
     */
    public function __construct()
    {
        global $pth;

        include_once "{$pth['folder']['classes']}FileEdit.php";
        $this->fileEdit = new \XH_PluginConfigFileEdit();
    }

    /**
     * The default action handler
     *
     * @return void
     */
    public function handleEdit()
    {
        echo $this->fileEdit->form();
    }

    /**
     * The save action handler
     *
     * @return void
     */
    public function handleSave()
    {
        echo $this->fileEdit->submit();
    }
}

<?php

namespace Pfw;

class ConfigAdminController extends AdminController
{
    private $fileEdit;

    public function __construct()
    {
        global $pth;

        include_once "{$pth['folder']['classes']}FileEdit.php";
        $this->fileEdit = new \XH_PluginConfigFileEdit();
    }

    public function handleEdit()
    {
        global $o;

        $o .= $this->fileEdit->form();
    }

    public function handleSave()
    {
        global $o;

        $o .= $this->fileEdit->submit();
    }
}

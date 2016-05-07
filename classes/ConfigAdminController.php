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
        echo $this->fileEdit->form();
    }

    public function handleSave()
    {
        echo $this->fileEdit->submit();
    }
}

<?php

namespace Pfw;

class LanguageAdminController extends AdminController
{
    private $fileEdit;

    public function __construct()
    {
        global $pth;

        include_once "{$pth['folder']['classes']}FileEdit.php";
        $this->fileEdit = new \XH_PluginLanguageFileEdit();
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

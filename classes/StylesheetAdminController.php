<?php

namespace Pfw;

class StylesheetAdminController extends AdminController
{
    private $fileEdit;

    public function __construct()
    {
        global $pth;

        include_once "{$pth['folder']['classes']}FileEdit.php";
        $this->fileEdit = new \XH_PluginTextFileEdit();
    }

    public function handleText()
    {
        echo $this->fileEdit->form();
    }

    public function handleTextsave()
    {
        echo $this->fileEdit->submit();
    }
}

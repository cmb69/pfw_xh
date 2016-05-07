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
        global $o;

        $o .= $this->fileEdit->form();
    }

    public function handleTextsave()
    {
        global $o;

        $o .= $this->fileEdit->submit();
    }
}

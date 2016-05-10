<?php

namespace Pfw;

class DefaultAdminController extends AdminController
{
    public function handleDefault()
    {
        $view = $this->htmlView('info');
        $view->title = ucfirst($this->plugin->name());
        $view->logo = $this->plugin->folder() . $this->plugin->name() . '.png';
        $view->version = $this->plugin->version();
        $view->copyright = $this->plugin->copyright();
        $view->systemCheck = $this->systemCheck();
        $view->render();
    }

    private function systemCheck()
    {
        $systemCheck = new SystemCheck();
        return $systemCheck
            ->mandatory()
                ->phpVersion('5.3')
            ->mandatory()
                ->noMagicQuotes()
                ->xhVersion('1.6');
    }
}

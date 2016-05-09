<?php

namespace Pfw;

class DefaultAdminController extends AdminController
{
    public function handleDefault()
    {
        $view = $this->htmlView('info');
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
                ->xhVersion('1.6')
            ->render();
    }
}

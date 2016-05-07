<?php

namespace Pfw;

class DefaultAdminController extends AdminController
{
    public function handleDefault()
    {
        $view = $this->view('info');
        $view->logo = $this->plugin->folder() . $this->plugin->name() . '.png';
        $view->copyright = $this->plugin->copyright();
        $view->version = $this->plugin->version();
        $view->render();
    }
}

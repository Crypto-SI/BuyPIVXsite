<?php

namespace App\Controllers;

use TEMP\View\View;

class Pages
{

    public function __construct(View $view)
    {
        $this->view = $view;
    }


    public function legal()
    {
        return $this->view->display('pages/legal');
    }

    public function privacy()
    {
        return $this->view->display('pages/privacy');
    }

    public function terms()
    {
        return $this->view->display('pages/terms');
    }
}

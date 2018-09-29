<?php

namespace App\Controllers;

use TEMP\View\View;

class Payment
{

    public function __construct(View $view)
    {
        $this->view = $view;
    }


    public function failed()
    {
        return $this->view->display('payment/failed');
    }

    public function success()
    {
        return $this->view->display('payment/success');
    }
}

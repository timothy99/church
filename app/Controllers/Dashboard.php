<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        $this->dashboard();
    }
    
    public function dashboard()
    {
        $view = view("dashboard/dashboard");
        echo $view;
    }

}

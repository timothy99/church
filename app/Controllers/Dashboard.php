<?php

namespace App\Controllers;

class Dashboard extends MyController
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

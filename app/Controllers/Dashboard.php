<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        $this->dashboard();
    }
    
    /**
     * [Description for dashboard]
     *
     * @return void
     * 
     * @author     timothy99 
     */
    public function dashboard() : void
    {
        $view = view("dashboard/dashboard");
        echo $view;
    }

}

<?php

namespace App\Controllers;

class User extends MyController
{
    public function login()
    {
        $view = view("user/login");
        echo $view;
    }

}

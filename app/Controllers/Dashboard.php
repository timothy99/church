<?php namespace App\Controllers;

use App\Libraries\Core\Query;

class Dashboard extends BaseController
{
	public function index()
	{
		$view = $this->dashboard();

		return $view;
	}
	
	public function dashboard()
	{
		$view = view("dashboard/dashboard"); 

		return $view;
	}
}

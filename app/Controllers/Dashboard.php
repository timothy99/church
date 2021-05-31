<?php namespace App\Controllers;

use App\Libraries\Core\Query;

class Dashboard extends BaseController
{
	public function index()
	{
		$data = array();

		$query = new Query();
		// $db_list = $query->dbList($data);
		
		echo "DB연결 테스트 완료";


	}
}

<?php namespace App\Models;

use CodeIgniter\Model;
use Throwable;
use App\Models\DateModel;

class BoardModel extends Model
{
    // 달력용 목록 정보 
    public function getList($meal_data)
    {
        $start_date = $meal_data["start_date"];
        $end_date = $meal_data["end_date"];

        $db = $this->db;

        $db_result = true;
        $db_message = "조회에 성공했습니다.";

        $builder = $db->table("gwt_meal");
        $builder->select("meal_menu as title");
        $builder->select("meal_date as start");
        $builder->select("meal_date as id");
        $builder->where("meal_date between '$start_date' and '$end_date' ");
        $builder->where("del_yn", "N");
        $db_list = $builder->get()->getResult(); // 쿼리 실행

        $model_result = array();
        $model_result["result"] = $db_result;
        $model_result["message"] = $db_message;
        $model_result["db_list"] = $db_list;

        return $model_result;
    }

}

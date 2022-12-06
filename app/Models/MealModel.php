<?php namespace App\Models;

use CodeIgniter\Model;
use Throwable;
use App\Models\DateModel;

class MealModel extends Model
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

    // 달력용 목록 정보 
    public function getMealList($page, $rows, $search_text)
    {
        $date_model = new DateModel();

        $db_result = true;
        $db_message = "조회에 성공했습니다.";

        $db = db_connect();
        $offset = ($page-1)*$rows; // 오프셋 계산
        $builder = $db->table("gwt_meal");
        if ($search_text != null) {
            $builder->like("meal_menu", $search_text);
        }
        $builder->where("del_yn", "N");
        $builder->orderBy("m_idx", "desc");
        $builder->limit($rows, $offset);
        $db_cnt = $builder->countAllResults(false); // 현제 데이터 총합
        $db_list = $builder->get()->getResultObject(); // 쿼리 실행
        foreach($db_list as $no => $val) {
            $ins_date = $val->ins_date;
            $ins_date = $date_model->convertTextToDate($ins_date, "1", "1");
            $db_list[$no]->ins_date = $ins_date;
        }

        $model_result = array();
        $model_result["result"] = $db_result;
        $model_result["message"] = $db_message;
        $model_result["db_list"] = $db_list;
        $model_result["db_cnt"] = $db_cnt;

        return $model_result;
    }

    public function getInfo($meal_date)
    {
        $db = $this->db;

        $db_result = true;
        $db_message = "조회에 성공했습니다.";

        $builder = $db->table("gwt_meal");
        $builder->select("*");
        $builder->where("meal_date", $meal_date);
        $builder->where("del_yn", "N");
        $db_info = $builder->get()->getFirstRow(); // 쿼리 실행

        $model_result = array();
        $model_result["result"] = $db_result;
        $model_result["message"] = $db_message;
        $model_result["db_info"] = $db_info;

        return $model_result;
    }

    public function procInsert($meal_data)
    {
        $meal_date = $meal_data["meal_date"];
        $meal_menu = $meal_data["meal_menu"];

        $db_result = true;
        $db_message = "입력에 성공했습니다.";

        $today = date("YmdHis");
        $ins_id = $_SESSION["user_session"]->user_id;

        $insert_id = -1;
        try {
            $db = $this->db;
            $db->transStart();

            $builder = $db->table("gwt_meal");
            $builder->set("meal_date", $meal_date);
            $builder->set("meal_menu", $meal_menu);
            $builder->set("del_yn", "N");
            $builder->set("ins_id", $ins_id);
            $builder->set("ins_date", $today);
            $builder->set("upd_id", $ins_id);
            $builder->set("upd_date", $today);
            $db_result = $builder->insert();
            $insert_id = $db->insertID();

            $db->transComplete();
        } catch (Throwable $t) {
            $db_result = false;
            $db_message = "입력에 오류가 발생했습니다.";
            logMessage($t->getMessage());
        }

        $model_result = array();
        $model_result["result"] = $db_result;
        $model_result["message"] = $db_message;
        $model_result["insert_id"] = $insert_id;

        return $model_result;
    }

    public function procUpdate($meal_data)
    {
        $meal_date = $meal_data["meal_date"];
        $meal_menu = $meal_data["meal_menu"];

        $db_result = true;
        $db_message = "입력에 성공했습니다.";

        $today = date("YmdHis");
        $upd_id = $_SESSION["user_session"]->user_id;

        $update_rows = -1;
        try {
            $db = $this->db;
            $db->transStart();

            $builder = $db->table("gwt_meal");
            $builder->set("meal_date", $meal_date);
            $builder->set("meal_menu", $meal_menu);
            $builder->set("del_yn", "N");
            $builder->set("upd_id", $upd_id);
            $builder->set("upd_date", $today);
            $builder->where("meal_date", $meal_date);
            $db_result = $builder->update();
            $update_rows = $db->affectedRows();

            $db->transComplete();
        } catch (Throwable $t) {
            $db_result = false;
            $db_message = "입력에 오류가 발생했습니다.";
            logMessage($t->getMessage());
        }

        $model_result = array();
        $model_result["result"] = $db_result;
        $model_result["message"] = $db_message;
        $model_result["update_rows"] = $update_rows;

        return $model_result;
    }

    public function procDelete($meal_date)
    {
        $db_result = true;
        $db_message = "삭제에 성공했습니다.";

        $today = date("YmdHis");
        $upd_id = $_SESSION["user_session"]->user_id;

        $update_rows = -1;
        try {
            $db = $this->db;
            $db->transStart();

            $builder = $db->table("gwt_meal");
            $builder->set("del_yn", "Y");
            $builder->set("upd_id", $upd_id);
            $builder->set("upd_date", $today);
            $builder->where("meal_date", $meal_date);
            $db_result = $builder->update();
            $update_rows = $db->affectedRows();

            $db->transComplete();
        } catch (Throwable $t) {
            $db_result = false;
            $db_message = "입력에 오류가 발생했습니다.";
            logMessage($t->getMessage());
        }

        $model_result = array();
        $model_result["result"] = $db_result;
        $model_result["message"] = $db_message;
        $model_result["update_rows"] = $update_rows;

        return $model_result;
    }
}

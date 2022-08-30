<?php namespace App\Models;

use CodeIgniter\Model;
use Throwable;
use App\Models\DateModel;

class BoardModel extends Model
{
    // 달력용 목록 정보 
    public function getBoardList($page, $rows, $search_text)
    {
        $date_model = new DateModel();

        $db_result = true;
        $db_message = "조회에 성공했습니다.";

        $db = db_connect();
        $offset = ($page-1)*$rows; // 오프셋 계산
        $builder = $db->table("gwt_board");
        if ($search_text != null) {
            $builder->like("title", $search_text);
            $builder->like("contents", $search_text);
        }
        $builder->where("del_yn", "N");
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

}

<?php namespace App\Models;

use CodeIgniter\Model;
use Throwable;
use App\Models\DateModel;

class BoardModel extends Model
{
    // 게시판 목록
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
        $builder->orderBy("board_idx", "desc");
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

    // 게시판 목록
    public function getBoardInfo($board_idx)
    {
        $date_model = new DateModel();

        $db_result = true;
        $db_message = "조회에 성공했습니다.";

        $db = db_connect();
        $builder = $db->table("gwt_board");
        $builder->where("board_idx", $board_idx);
        $builder->where("del_yn", "N");
        $board_info = $builder->get()->getFirstRow();

        $ins_date = $board_info->ins_date;
        $ins_date = $date_model->convertTextToDate($ins_date, "1", "1");
        $board_info->ins_date = $ins_date;

        $notice_yn = $board_info->notice_yn;
        if($notice_yn == "Y") {
            $notice_checked = "checked";
        } else {
            $notice_checked = null;
        }
        $board_info->notice_checked = $notice_checked;

        $secret_yn = $board_info->secret_yn;
        if($secret_yn == "Y") {
            $secret_checked = "checked";
        } else {
            $secret_checked = null;
        }
        $board_info->secret_checked = $secret_checked;

        $model_result = array();
        $model_result["result"] = $db_result;
        $model_result["message"] = $db_message;
        $model_result["board_info"] = $board_info;

        return $model_result;
    }

    // 게시판 입력
    public function insertBoard($board_data)
    {
        // 게시판 입력과 관련된 기본 정보
        $user_id = getUserSessionInfo("user_id");
        $today = date("YmdHis");

        $notice_yn = $board_data["notice_yn"];
        $secret_yn = $board_data["secret_yn"];
        $title = $board_data["title"];
        $contents = $board_data["contents"];
        $http_link = $board_data["http_link"];

        $result = true;
        $message = "입력이 잘 되었습니다";

        try {
            $db = db_connect();
            $db->transStart();
            $builder = $db->table("gwt_board");

            $builder->set("notice_yn", $notice_yn);
            $builder->set("secret_yn", $secret_yn);
            $builder->set("title", $title);
            $builder->set("contents", $contents);
            $builder->set("http_link", $http_link);
            $builder->set("del_yn", "N");
            $builder->set("ins_id", $user_id);
            $builder->set("ins_date", $today);
            $builder->set("upd_id", $user_id);
            $builder->set("upd_date", $today);
            $result = $builder->insert();
            $insert_id = $db->insertID();
            $db->transComplete();
        } catch (Throwable $t) {
            $result = false;
            $message = "입력에 오류가 발생했습니다.";
            logMessage($t->getMessage());
        }

        $model_result = array();
        $model_result["result"] = $result;
        $model_result["message"] = $message;
        $model_result["insert_id"] = $insert_id;

        return $model_result;
    }

    // 게시판 입력
    public function updateBoard($board_data)
    {
        // 게시판 입력과 관련된 기본 정보
        $user_id = getUserSessionInfo("user_id");
        $today = date("YmdHis");

        $notice_yn = $board_data["notice_yn"];
        $secret_yn = $board_data["secret_yn"];
        $title = $board_data["title"];
        $contents = $board_data["contents"];
        $http_link = $board_data["http_link"];
        $board_idx = $board_data["board_idx"];

        $result = true;
        $message = "입력이 잘 되었습니다";

        try {
            $db = db_connect();
            $db->transStart();
            $builder = $db->table("gwt_board");

            $builder->set("notice_yn", $notice_yn);
            $builder->set("secret_yn", $secret_yn);
            $builder->set("title", $title);
            $builder->set("contents", $contents);
            $builder->set("http_link", $http_link);
            $builder->set("upd_id", $user_id);
            $builder->set("upd_date", $today);
            $builder->where("board_idx", $board_idx);
            $result = $builder->update();
            $db->transComplete();
        } catch (Throwable $t) {
            $result = false;
            $message = "입력에 오류가 발생했습니다.";
            logMessage($t->getMessage());
        }

        $model_result = array();
        $model_result["result"] = $result;
        $model_result["message"] = $message;

        return $model_result;
    }

    // 게시판 삭제
    public function deleteBoard($board_idx)
    {
        // 게시판 입력과 관련된 기본 정보
        $user_id = getUserSessionInfo("user_id");
        $today = date("YmdHis");

        $result = true;
        $message = "입력이 잘 되었습니다";

        try {
            $db = db_connect();
            $db->transStart();
            $builder = $db->table("gwt_board");

            $builder->set("del_yn", "Y");
            $builder->set("upd_id", $user_id);
            $builder->set("upd_date", $today);
            $builder->where("board_idx", $board_idx);
            $result = $builder->update();
            $db->transComplete();
        } catch (Throwable $t) {
            $result = false;
            $message = "입력에 오류가 발생했습니다.";
            logMessage($t->getMessage());
        }

        $model_result = array();
        $model_result["result"] = $result;
        $model_result["message"] = $message;

        return $model_result;
    }
}

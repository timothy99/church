<?php namespace App\Models;

use CodeIgniter\Model;
use Throwable;
use stdClass;

class UserModel extends Model
{
    public function __construct()
    {
        helper("log_helper");
    }

    // 사용자 정보 입력
    public function insertUserInfo($data)
    {
        $user_name = $data["user_name"];
        $user_id = $data["user_id"];
        $user_password = $data["user_password"];

        $db_result = true;
        $db_message = "입력이 잘 되었습니다";
        $insert_id = 0;

        try {
            $db = \Config\Database::connect();

            $db->transStart();

            $builder = $db->table("csl_user");
            $builder->set("user_id", $user_id);
            $builder->set("user_name", $user_name);
            $builder->set("user_password", $user_password);
            $builder->set("use_yn", "Y");
            $builder->set("del_yn", "N");
            $builder->set("ins_id", $user_id);
            $builder->set("ins_date", "now()", false);
            $builder->set("upd_id", $user_id);
            $builder->set("upd_date", "now()", false);
            $db_result = $builder->insert()->resultID;

            $db->transComplete();

            $insert_id = $db->insertID();
            logLastQuery(); // 현재 쿼리 로그 남기기
        } catch (Throwable $t) {
            $db_result = false;
            $db_message = "입력에 오류가 발생했습니다.";
        }

        $model_result = array();
        $model_result["result"] = $db_result;
        $model_result["message"] = $db_message;
        $model_result["insert_id"] = $insert_id;

        return $model_result;
    }

    // 로그인 할때 사용자가 맞는지 정보 갖고 오기
    public function getLoginInfo($data)
    {
        $db_result = true;
        $db_message = "조회에 성공했습니다.";
        $db_info = new stdClass();

        $user_id = $data["user_id"];
        $user_password = $data["user_password"];

        try {
            $db = \Config\Database::connect();

            $builder = $db->table("csl_user");
            $builder->select("user_idx");
            $builder->select("user_id");
            $builder->where("user_id", $user_id);
            $builder->where("user_password", $user_password);
            $builder->where("use_yn", "Y");
            $builder->where("del_yn", "N");
            $db_info = $builder->get()->getFirstRow(); // 쿼리 실행
        } catch(Throwable $t) {
            $db_result = false;
            $db_message = "조회에 오류가 발생했습니다.";
        }

        $model_result = array();
        $model_result["result"] = $db_result;
        $model_result["message"] = $db_message;
        $model_result["db_info"] = $db_info;

        return $model_result;
    }

}

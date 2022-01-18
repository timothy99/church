<?php namespace App\Models;

use CodeIgniter\Model;
use Throwable;

class UserModel extends Model
{
    // 사용자 정보 입력
    public function insertUserInfo($data)
    {
        helper("log_helper");

        $user_name = $data["user_name"];
        $user_id = $data["user_id"];
        $user_password = $data["user_password"];

        $db_result = true;
        $db_message = "입력이 잘 되었습니다";
        $insert_id = 0;
        $affected_rows = 0;

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
        $model_result["affected_rows"] = $affected_rows;

        return $model_result;
    }

}

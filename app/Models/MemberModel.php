<?php namespace App\Models;

use CodeIgniter\Model;
use Throwable;
use stdClass;
use App\Models\SecurityModel;
use App\Models\FileModel;

class MemberModel extends Model
{
    // 사용자 목록 갖고 오기
    public function getMemberList($page, $rows, $search_text)
    {
        $security_model= new SecurityModel();
        $db = $this->db;

        $offset = ($page-1)*$rows; // 오프셋 계산

        $db_result = true;
        $db_message = "조회에 성공했습니다.";

        $builder = $db->table("gwt_user");

        if ($search_text != null) {
            $builder->like("user_id", $search_text);
        }

        $builder->where("del_yn", "N");
        $builder->limit($rows, $offset);
        $db_cnt = $builder->countAllResults(false); // 현제 데이터 총합
        $db_list = $builder->get()->getResultObject(); // 쿼리 실행
        foreach ($db_list as $no => $val) { // 암호화 데이터 복호화
            $user_name = $val->user_name;
            $user_name_dec = $security_model->getTextDecrypt($user_name); // 헬퍼를 이용한 암호화 데이터 복호화
            $db_list[$no]->user_name = $user_name_dec;
        }

        $model_result = array();
        $model_result["result"] = $db_result;
        $model_result["message"] = $db_message;
        $model_result["db_list"] = $db_list;
        $model_result["db_cnt"] = $db_cnt;

        return $model_result;
    }

    // 사용자 정보 갖고 오기
    public function getMemberInfo($user_idx)
    {
        $security_model = new SecurityModel();

        $db_result = true;
        $db_message = "조회에 성공했습니다.";
        $db_info = new stdClass();

        $user_idx = (int)$user_idx;

        try {
            $db = db_connect();
            $builder = $db->table("gwt_user");
            $builder->select("user_idx");
            $builder->select("user_id");
            $builder->select("user_name");
            $builder->select("use_yn");
            $builder->select("ins_date");
            $builder->select("admin_yn");
            $builder->select("profile_image");
            $builder->select("count(*) as cnt");
            $builder->where("use_yn", "Y");
            $builder->where("del_yn", "N");
            $db_info = $builder->get()->getFirstRow(); // 쿼리 실행
        } catch (Throwable $t) {
            $db_result = false;
            $db_message = "조회에 오류가 발생했습니다.";
        }

        $db_info->user_name = $security_model->getTextDecrypt($db_info->user_name);

        $model_result = array();
        $model_result["result"] = $db_result;
        $model_result["message"] = $db_message;
        $model_result["db_info"] = $db_info;

        return $model_result;
    }

    // 사용자 정보 입력
    public function updateMemberInfo($data)
    {
        $user_idx = $data["user_idx"];
        $user_name = $data["user_name"];
        $admin_yn = $data["admin_yn"];
        $profile_image = $data["profile_image"];
        $use_yn = $data["use_yn"];
        $upd_id = $data["upd_id"];

        $db_result = true;
        $db_message = "입력이 잘 되었습니다";
        $affected_rows = 0;

        try {
            $db = db_connect();
            $db->transStart();
            $builder = $db->table("gwt_user");
            $builder->set("user_name", $user_name);
            $builder->set("admin_yn", $admin_yn);
            $builder->set("use_yn", $use_yn);
            $builder->set("profile_image", $profile_image);
            $builder->set("upd_id", $upd_id);
            $builder->set("upd_date", "now()", false);
            $builder->where("user_idx", $user_idx);
            $db_result = $builder->update();
            $db->transComplete();
            $affected_rows = $db->affectedRows();
        } catch (Throwable $t) {
            $db_result = false;
            $db_message = "입력에 오류가 발생했습니다.";
        }

        $model_result = array();
        $model_result["result"] = $db_result;
        $model_result["message"] = $db_message;
        $model_result["affected_rows"] = $affected_rows;

        return $model_result;
    }

    // 사용자 정보 입력
    public function deleteMemberInfo($data)
    {
        $user_idx = $data["user_idx"];
        $upd_id = $data["upd_id"];

        $db_result = true;
        $db_message = "입력이 잘 되었습니다";
        $affected_rows = 0;

        try {
            $db = \Config\Database::connect();

            $db->transStart();

            $builder = $db->table("gwt_user");
            $builder->set("admin_yn", "N");
            $builder->set("use_yn", "N");
            $builder->set("del_yn", "Y");
            $builder->set("upd_id", $upd_id);
            $builder->set("upd_date", "now()", false);
            $builder->where("user_idx", $user_idx);
            $db_result = $builder->update();

            $db->transComplete();
            $affected_rows = $db->affectedRows();
        } catch (Throwable $t) {
            $db_result = false;
            $db_message = "입력에 오류가 발생했습니다.";
        }

        $model_result = array();
        $model_result["result"] = $db_result;
        $model_result["message"] = $db_message;
        $model_result["affected_rows"] = $affected_rows;

        return $model_result;
    }

    /**
     * 사용자 프로필 이미지 불러오기
     * 기존의 getMemberInfo 를 사용하면 세션에 너무 큰 이미지 정보까지 저장해서 따로 함수 작성
    */
    public function getMemberProfileImageInfo()
    {
        $file_model = new FileModel();

        // 세션의 정보중 아이디를 갖고 옵니다.
        $session = \Config\Services::session();
        $user_session = $session->get("user_session");
        $user_idx = $user_session->user_idx;

        $db = db_connect();
        $builder = $db->table("gwt_user");
        $builder->select("profile_image");
        $builder->where("user_idx", $user_idx);
        $db_info = $builder->get()->getFirstRow(); // 쿼리 실행

        $image_base64 = $file_model->getImageFileInfo($db_info->profile_image);
        $image_base64_html = "<img src=\"".$image_base64."\">";

        return $image_base64_html;
    }

}

<?php

namespace App\Controllers;

use App\Models\MemberModel;
use App\Models\PagingModel;

class Member extends BaseController
{
    /**
     * [Description for list]
     * 회원목록
     * @return [type]
     * 
     * @author     timothy99
     */
    public function list()
    {
        $member_model = new MemberModel();
        $paging_model = new PagingModel();

        $rows = 10;
        $page = $this->request->getGet("p") ?? 1;
        $search_text = $this->request->getGet("q", FILTER_SANITIZE_SPECIAL_CHARS);
        $model_result = $member_model->getMemberList($page, $rows, $search_text);

        $cnt = $model_result["db_cnt"]; // 데이터 총합
        $paging = $paging_model->getPaging($page, $rows, $cnt);
        $paging_view = view("paging/paging", ["paging"=>$paging, "q"=>$search_text, "href_link"=>"/member/list"]); // 페이징 뷰

        $proc_result = array();
        $proc_result["result"] = $model_result["result"];
        $proc_result["message"] = $model_result["message"];
        $proc_result["user_list"] = $model_result["db_list"];
        $proc_result["cnt"] = $cnt;
        $proc_result["paging"] = $paging;
        $proc_result["start_row"] = ($page-1)*$rows+1;
        $proc_result["p"] = $page;
        $proc_result["q"] = $search_text;
        $proc_result["paging_view"] = $paging_view; // 페이징 뷰

        $view = view("member/list", $proc_result);
        echo $view;
    }

    /**
     * [Description for userInfo]
     * 회원보기
     *
     * @return [type]
     * 
     * @author     timothy99
     */
    public function view()
    {
        $member_model = new MemberModel();

        $user_idx = $this->request->uri->getSegment(3);
        $model_result = $member_model->getMemberInfo($user_idx);

        $proc_result = array();
        $proc_result["result"] = $model_result["result"];
        $proc_result["message"] = $model_result["message"];
        $proc_result["user_info"] = $model_result["db_info"];

        $view = view("member/view", $proc_result);
        echo $view;
    }

    /**
     * [Description for userEdit]
     * 회원 정보 수정
     *
     * @return [type]
     * 
     * @author     timothy99
     */
    public function edit()
    {
        $member_model = new MemberModel();

        $user_idx = $this->request->uri->getSegment(3);
        $model_result = $member_model->getMemberInfo($user_idx);
        $member_info = $model_result["db_info"];

        $proc_result = array();
        $proc_result["result"] = $model_result["result"];
        $proc_result["message"] = $model_result["message"];
        $proc_result["member_info"] = $member_info;

        $view = view("member/edit", $proc_result);
        return $view;
    }


    /**
     * [Description for update]
     *
     * @return json
     * 
     * @author     timothy99 
     */
    public function update()
    {
        $member_model = new MemberModel();

        $result = true;
        $message = "회원수정이 완료되었습니다.";

        $user_idx = $this->request->getPost("user_idx", FILTER_SANITIZE_SPECIAL_CHARS);
        $user_name = $this->request->getPost("user_name", FILTER_SANITIZE_SPECIAL_CHARS);
        $admin_yn = $this->request->getPost("admin_yn", FILTER_SANITIZE_SPECIAL_CHARS);
        $use_yn = $this->request->getPost("use_yn", FILTER_SANITIZE_SPECIAL_CHARS);
        $profile_image = $this->request->getPost("profile_image", FILTER_SANITIZE_SPECIAL_CHARS);

        // 세션의 정보중 아이디를 갖고 옵니다.
        $session = \Config\Services::session();
        $user_session = $session->get("user_session");
        $upd_id = $user_session->user_id;

        $user_name = trim($user_name);
        if ($user_name == null) {
            $result = false;
            $message = "이름을 입력해주세요.";
        }

        if ($result == true) {
            $data = array();
            $data["user_idx"] = $user_idx;
            $data["user_name"] = $user_name;
            $data["admin_yn"] = $admin_yn;
            $data["profile_image"] = $profile_image;
            $data["use_yn"] = $use_yn;
            $data["upd_id"] = $upd_id;

            $model_result = $member_model->updateMemberInfo($data);
            $result = $model_result["result"];
            if ($result == false) {
                $message = $model_result["message"];
            }
        }

        $proc_result = array();
        $proc_result["result"] = $result;
        $proc_result["message"] = $message;

        echo json_encode($proc_result);
    }

    /**
     * [Description for userDelete]
     * 회원삭제
     * @return [type]
     * 
     * @author     timothy99
     */
    public function delete()
    {
        $member_model = new MemberModel();

        $result = true;
        $message = "회원삭제가 완료되었습니다.";

        $user_idx = $this->request->getPost("user_idx", FILTER_SANITIZE_SPECIAL_CHARS);

        $session = \Config\Services::session();

        // 세션의 정보중 아이디를 갖고 옵니다.
        $user_session = $session->get("user_session");
        $upd_id = $user_session["user_id"];

        $data = array();
        $data["user_idx"] = $user_idx;
        $data["upd_id"] = $upd_id;

        $model_result = $member_model->deleteUserInfo($data);
        $result = $model_result["result"];
        if ($result == false) {
            $message = $model_result["message"];
        }

        $proc_result = array();
        $proc_result["result"] = $result;
        $proc_result["message"] = $message;

        echo json_encode($proc_result);
    }
}

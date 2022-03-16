<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\MessageModel;
use App\Models\PagingModel;
use App\Models\SecurityModel;

class User extends BaseController
{
    /**
     * [Description for login]
     * 로그인 페이지
     *
     * @return void
     * 
     * @author     timothy99
     */
    public function login() : void
    {
        $view = view("user/login");
        echo $view;
    }

    /**
     * [Description for logout]
     * 로그인 페이지
     *
     * @return void
     * 
     * @author     timothy99
     */
    public function logout() : void
    {
        $session = \Config\Services::session();
        $session->destroy(); // 세션 삭제
        $base_url = base_url(); // 기본 url 입력
        header("Location: $base_url/user/login"); // 로그인 하는 사이트로 보냄
        exit;
    }

    /**
     * [Description for loginProc]
     * 로그인 처리
     *
     * @return [type]
     * 
     * @author     timothy99
     */
    public function loginProc()
    {
        $user_model = new UserModel();
        $security_model = new SecurityModel();

        $session = \Config\Services::session(); // 세션을 초기화 합니다.

        $result = true;
        $message = "로그인이 완료되었습니다.";

        $user_id = $this->request->getPost("user_id", FILTER_SANITIZE_EMAIL);
        $user_password = $this->request->getPost("user_password", FILTER_SANITIZE_SPECIAL_CHARS);

        $user_id = trim($user_id);

        if ($user_id == null) {
            $result = false;
            $message = "아이디를 입력해주세요.";
        }

        if ($user_password == null) {
            $result = false;
            $message = "암호를 입력해주세요.";
        }

        $user_password_enc = $security_model->getPasswordEncrypt($user_password); // 암호의 일방향 암호화

        $data = array();
        $data["user_id"] = $user_id;
        $data["user_password"] = $user_password_enc;

        $model_result = $user_model->getLoginInfo($data);
        $result = $model_result["result"];
        if ($result == false) {
            $message = $model_result["message"];
        }

        $user_login_info = $model_result["db_info"];
        if ($user_login_info->admin_yn == "N") {
            $result = false;
            $message = "아직 관리자로 승인되지 않았습니다";
        }

        if ($user_login_info->cnt == 0) {
            $result = false;
            $message = "아이디나 암호를 확인해주시기 바랍니다.";
        }

        if ($result == true) {
            // 세션에 입력할 데이터
            $user_session = array();
            $user_session["user_idx"] = $user_login_info->user_idx;
            $user_session["user_id"] = $user_login_info->user_id;

            // 세션에 아이디와 idx입력
            $session->set("user_session", $user_session);
        }

        $proc_result = array();
        $proc_result["result"] = $result;
        $proc_result["message"] = $message;

        echo json_encode($proc_result);
    }

    /**
     * [Description for forgot]
     * 암호 분실 확인 - 아직 작업중
     *
     * @return [type]
     * 
     * @author     timothy99
     */
    public function forgot()
    {
        // do something
    }

    /**
     * [Description for register]
     * 회원가입 화면
     *
     * @return [type]
     * 
     * @author     timothy99
     */
    public function register()
    {
        $view = view("user/register");
        echo $view;
    }

    /**
     * [Description for registerProc]
     * 회원가입 처리
     * @return [type]
     * 
     * @author     timothy99
     */
    public function registerProc()
    {
        $user_model = new UserModel();
        $message_model = new MessageModel();
        $security_model = new SecurityModel();

        $result = true;
        $message = "회원가입이 완료되었습니다.";

        $user_name = $this->request->getPost("user_name", FILTER_SANITIZE_SPECIAL_CHARS);
        $user_id = $this->request->getPost("user_id", FILTER_SANITIZE_EMAIL);
        $user_password = $this->request->getPost("user_password", FILTER_SANITIZE_SPECIAL_CHARS);
        $user_password2 = $this->request->getPost("user_password2", FILTER_SANITIZE_SPECIAL_CHARS);
        $user_terms = $this->request->getPost("user_terms", FILTER_SANITIZE_SPECIAL_CHARS);

        $user_name = trim($user_name);
        $user_id = trim($user_id);

        if ($user_name == null) {
            $result = false;
            $message = "이름을 입력해주세요.";
        }

        if ($user_id == null) {
            $result = false;
            $message = "아이디를 입력해주세요.";
        }

        if ($user_password != $user_password2) {
            $result = false;
            $message =  "입력된 암호가 다릅니다.";
        }

        if ($user_terms != "agree") {
            $result = false;
            $message = "약관에 동의해 주세요.";
        }

        // 아이디 중복체크
        $model_result = $user_model->getUserIdCheck($user_id);
        $result = $model_result["result"];
        $message = $model_result["message"];

        if ($result == true) {
            // 데이터 암호화
            $user_name_enc = $security_model->getTextEncrypt($user_name); // 이름 암호화
            $user_password_enc = $security_model->getPasswordEncrypt($user_password); // 암호의 일방향 암호화

            $data = array();
            $data["user_name"] = $user_name_enc;
            $data["user_id"] = $user_id;
            $data["user_password"] = $user_password_enc;

            $model_result = $user_model->insertUserInfo($data);
            $result = $model_result["result"];
            if ($result == false) {
                $message = $model_result["message"];
            }
        }

        // 회원가입이 완료된 경우 이메일을 발송합니다.
        if ($result == true) {
            $from = env("email.smtp.user");
            $from_name = env("email.smtp.name");
            $title = "가입을 환영합니다";
            $contents = "가입을 환영합니다\n우리의 소중한 사람이 되어주셔서 고맙습니다";
            $message_model->sendEmail($from, $from_name, $user_id, $title, $contents);
        }

        $proc_result = array();
        $proc_result["result"] = $result;
        $proc_result["message"] = $message;

        echo json_encode($proc_result);
    }

    /**
     * [Description for userList]
     * 회원목록
     * @return [type]
     * 
     * @author     timothy99
     */
    public function userList()
    {
        $user_model = new UserModel();
        $paging_model = new PagingModel();

        $rows = 10;
        $page = $this->request->getGet("p") ?? 1;
        $search_text = $this->request->getGet("q", FILTER_SANITIZE_SPECIAL_CHARS);
        $model_result = $user_model->getUserList($page, $rows, $search_text);

        $cnt = $model_result["db_cnt"]; // 데이터 총합
        $paging = $paging_model->getPaging($page, $rows, $cnt);

        $proc_result = array();
        $proc_result["result"] = $model_result["result"];
        $proc_result["message"] = $model_result["message"];
        $proc_result["user_list"] = $model_result["db_list"];
        $proc_result["cnt"] = $cnt;
        $proc_result["paging"] = $paging;
        $proc_result["start_row"] = ($page-1)*$rows+1;
        $proc_result["p"] = $page;
        $proc_result["q"] = $search_text;

        $view = view("user/userList", $proc_result);
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
    public function userInfo()
    {
        $user_model = new UserModel();

        $user_idx = $this->request->uri->getSegment(3);
        $model_result = $user_model->getUserInfo($user_idx);

        $proc_result = array();
        $proc_result["result"] = $model_result["result"];
        $proc_result["message"] = $model_result["message"];
        $proc_result["user_info"] = $model_result["db_info"];

        $view = view("user/userInfo", $proc_result);
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
    public function userEdit()
    {
        $user_model = new UserModel();

        $user_idx = $this->request->uri->getSegment(3);
        $model_result = $user_model->getUserInfo($user_idx);

        $proc_result = array();
        $proc_result["result"] = $model_result["result"];
        $proc_result["message"] = $model_result["message"];
        $proc_result["user_info"] = $model_result["db_info"];

        $view = view("user/userEdit", $proc_result);
        echo $view;
    }

    /**
     * [Description for userEditProc]
     * 회원 정보 수정 처리
     * @return [type]
     * 
     * @author     timothy99
     */
    public function userEditProc()
    {
        $user_model = new UserModel();
        $security_model= new SecurityModel();

        $result = true;
        $message = "회원수정이 완료되었습니다.";

        $user_idx = $this->request->getPost("user_idx", FILTER_SANITIZE_SPECIAL_CHARS);
        $user_name = $this->request->getPost("user_name", FILTER_SANITIZE_SPECIAL_CHARS);
        $admin_yn = $this->request->getPost("admin_yn", FILTER_SANITIZE_SPECIAL_CHARS);
        $use_yn = $this->request->getPost("use_yn", FILTER_SANITIZE_SPECIAL_CHARS);

        $session = \Config\Services::session();

        // 세션의 정보중 아이디를 갖고 옵니다.
        $user_session = $session->get("user_session");
        $upd_id = $user_session["user_id"];

        $user_name = trim($user_name);

        if ($user_name == null) {
            $result = false;
            $message = "이름을 입력해주세요.";
        }

        if ($result == true) {
            // 데이터 암호화
            $user_name_enc = $security_model->getTextEncrypt($user_name); // 이름 암호화

            $data = array();
            $data["user_idx"] = $user_idx;
            $data["user_name"] = $user_name_enc;
            $data["admin_yn"] = $admin_yn;
            $data["use_yn"] = $use_yn;
            $data["upd_id"] = $upd_id;

            $model_result = $user_model->updateUserInfo($data);
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
    public function userDelete()
    {
        $user_model = new UserModel();

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

        $model_result = $user_model->deleteUserInfo($data);
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

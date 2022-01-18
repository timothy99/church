<?php

namespace App\Controllers;

use App\Models\UserModel;

class User extends MyController
{
    public function __construct()
    {
        helper("security_helper"); // 암호화 관련 헬퍼
    }

    // 로그인 페이지
    public function login()
    {
        $view = view("user/login");
        echo $view;
    }

    // 로그인 페이지
    public function logout()
    {
        $session = \Config\Services::session();
        $session->destroy(); // 세션 삭제
        $base_url = base_url(); // 기본 url 입력
        header("Location: $base_url/user/login"); // 로그인 하는 사이트로 보냄
        exit;
    }

    // 로그인 처리
    public function loginProc()
    {
        $result = true;
        $message = "로그인이 완료되었습니다.";

        $user_id = $this->request->getPost("user_id", FILTER_SANITIZE_EMAIL);
        $user_password = $this->request->getPost("user_password", FILTER_SANITIZE_SPECIAL_CHARS);

        $user_id = trim($user_id);

        if($user_id == null) {
            $result = false;
            $message = "아이디를 입력해주세요.";
        }

        if($user_password == null) {
            $result = false;
            $message = "암호를 입력해주세요.";
        }

        $user_password_enc = getPasswordEncrypt($user_password); // 암호의 일방향 암호화

        $user_model = new UserModel();

        $data = array();
        $data["user_id"] = $user_id;
        $data["user_password"] = $user_password_enc;

        $model_result = $user_model->getLoginInfo($data);
        $result = $model_result["result"];
        if($result == false) {
            $message = $model_result["message"];
        }

        $user_login_info = $model_result["db_info"];

        // 세션에 입력할 데이터
        $user_session = array();
        $user_session["user_idx"] = $user_login_info->user_idx;
        $user_session["user_id"] = $user_login_info->user_id;

        // 세션에 아이디와 idx입력
        $session = \Config\Services::session(); // 세션을 초기화 합니다.
        $session->set("user_session", $user_session);

        $proc_result = array();
        $proc_result["result"] = $result;
        $proc_result["message"] = $message;

        echo json_encode($proc_result);
    }

    // 암호 분실 확인
    public function forgot()
    {
        // do something
    }

    // 회원가입
    public function register()
    {
        $view = view("user/register");
        echo $view;
    }

    // 회원가입
    public function registerProc()
    {
        $result = true;
        $message = "회원가입이 완료되었습니다.";

        $user_name = $this->request->getPost("user_name", FILTER_SANITIZE_SPECIAL_CHARS);
        $user_id = $this->request->getPost("user_id", FILTER_SANITIZE_EMAIL);
        $user_password = $this->request->getPost("user_password", FILTER_SANITIZE_SPECIAL_CHARS);
        $user_password2 = $this->request->getPost("user_password2", FILTER_SANITIZE_SPECIAL_CHARS);
        $user_terms = $this->request->getPost("user_terms", FILTER_SANITIZE_SPECIAL_CHARS);

        $user_name = trim($user_name);
        $user_id = trim($user_id);

        if($user_name == null) {
            $result = false;
            $message = "이름을 입력해주세요.";
        }

        if($user_id == null) {
            $result = false;
            $message = "아이디를 입력해주세요.";
        }

        if($user_password != $user_password2) {
            $result = false;
            $message =  "입력된 암호가 다릅니다.";
        }

        if($user_terms != "agree") {
            $result = false;
            $message = "약관에 동의해 주세요.";
        }

        if($result == true) {
            // 데이터 암호화
            $user_name_enc = getTextEncrypt($user_name); // 이름 암호화
            $user_password_enc = getPasswordEncrypt($user_password); // 암호의 일방향 암호화

            $user_model = new UserModel();

            $data = array();
            $data["user_name"] = $user_name_enc;
            $data["user_id"] = $user_id;
            $data["user_password"] = $user_password_enc;

            $model_result = $user_model->insertUserInfo($data);
            $result = $model_result["result"];
            if($result == false) {
                $message = $model_result["message"];
            }
        }

        $proc_result = array();
        $proc_result["result"] = $result;
        $proc_result["message"] = $message;

        echo json_encode($proc_result);
    }

}

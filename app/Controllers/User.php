<?php

namespace App\Controllers;

use App\Models\UserModel;

class User extends MyController
{
    // 로그인 페이지
    public function login()
    {
        $view = view("user/login");
        echo $view;
    }

    // 암호 분실 확인
    public function forgot()
    {
        // 
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
            $user_model = new UserModel();

            $data = array();
            $data["user_name"] = $user_name;
            $data["user_id"] = $user_id;
            $data["user_password"] = $user_password;

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

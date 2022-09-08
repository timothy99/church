<?php

namespace App\Controllers;

use App\Models\MemberModel;
use App\Models\MessageModel;
use App\Models\SecurityModel;
use stdClass;

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
    public function login()
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
    public function logout()
    {
        $session = $this->session;
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
    public function signin()
    {
        $member_model = new MemberModel();
        $security_model = new SecurityModel();

        $session = $this->session; // 세션을 초기화 합니다.

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

        $model_result = $member_model->getLoginInfo($data);
        $result = $model_result["result"];
        if ($result == false) { // 회원정보 조회에 오류가 발생한 경우
            $message = $model_result["message"];
        } else { // 조회에 성공한 경우
            $user_login_info = $model_result["db_info"];
            if ($user_login_info->cnt == 0) { // 조회에 성공했으나 결과가 없는 경우
                $result = false;
                $message = "아이디나 암호를 확인해주시기 바랍니다.";
            }

            if ($result == true) { // 세션에 데이터 입력
                $user_idx = $user_login_info->user_idx;
                $model_result = $member_model->getMemberInfo($user_idx);
                $member_info = $model_result["db_info"];
                $profile_image_base64 = $member_model->getMemberProfileImageInfo($user_idx);
                $member_info->profile_image_base64 = $profile_image_base64;
                $session->set("user_session", $member_info);
            }
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
    public function signup()
    {
        $member_model = new MemberModel();
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

        if($result == true) { 
            // 아이디 중복체크
            $model_result = $member_model->getUserIdCheck($user_id);
            $result = $model_result["result"];
            $message = $model_result["message"];
        }

        if ($result == true) {
            // 데이터 암호화
            $user_name_enc = $security_model->getTextEncrypt($user_name); // 이름 암호화
            $user_password_enc = $security_model->getPasswordEncrypt($user_password); // 암호의 일방향 암호화

            $data = array();
            $data["user_name"] = $user_name_enc;
            $data["user_id"] = $user_id;
            $data["user_password"] = $user_password_enc;

            $model_result = $member_model->insertUserInfo($data);
            $result = $model_result["result"];
            $message = $model_result["message"];
        }

        // // 회원가입이 완료된 경우 이메일을 발송합니다.
        // if ($result == true) {
        //     $from = env("email.smtp.user");
        //     $from_name = env("email.smtp.name");
        //     $title = "가입을 환영합니다";
        //     $contents = "가입을 환영합니다\n우리의 소중한 사람이 되어주셔서 고맙습니다";
        //     $message_model->sendEmail($from, $from_name, $user_id, $title, $contents);
        // }

        if($result == true) {
            $message = "회원가입을 축하합니다";
        }

        $proc_result = array();
        $proc_result["result"] = $result;
        $proc_result["message"] = $message;

        echo json_encode($proc_result);
    }

}

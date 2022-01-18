<?php

namespace App\Controllers;

use App\Models\AuthorityModel;

class MyController extends BaseController
{
    public function __construct()
    {
        // 기본적으로 항상 로깅헬퍼는 사용할 것이라 생성자에 선언했습니다.
        helper("log_helper");

        $this->authorityCheck(); // 권한체크
    }

    // 권한체크 로직
    public function authorityCheck()
    {
        $need_login = false;

        $session = \Config\Services::session();

        $authority_model = new AuthorityModel();

        $is_check_session = $authority_model->checkSession();
        $is_check_url = $authority_model->checkUrl();
        if ($is_check_session == false && $is_check_url == false) {
            $need_login = true;
        }

        // 로그인을 해야 한다면
        if ($need_login == true) {
            $session->destroy(); // 세션 삭제
            $base_url = base_url(); // 기본 url 입력
            header("Location: $base_url/user/login"); // 로그인 하는 사이트로 보냄
            exit;
        }
    }

}
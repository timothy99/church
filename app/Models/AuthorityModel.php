<?php namespace App\Models;

use CodeIgniter\Model;

class AuthorityModel extends Model
{
    // 아이피(ip) 체크
    public function checkIp()
    {
        $is_check_ip = false;
        $ip_address = $_SERVER["REMOTE_ADDR"];

        $ip_arr = array();
        $ip_arr = explode("|", env("ip.address"));

        if (in_array($ip_address, $ip_arr)) {
            $is_check_ip = true;
        }

        if ($is_check_ip == false) {
            echo "not the type";
            exit;
        }
    }

    // 세션정보를 체크해서 로그인이 되었는지 확인
    public function checkSession()
    {
        // 세션을 초기화 합니다.
        $session = \Config\Services::session();

        // 세션의 정보중 아이디를 갖고 옵니다.
        $user_session = $session->get("user_session");
        $is_check_session = isset($user_session["user_id"]);

        return $is_check_session;
    }

    // 로그인없이도 접근이 허용되는 url인지 확인한다
    public function checkLogin()
    {
        // 세션을 초기화 합니다.
        $session = \Config\Services::session();

        // 세션의 정보중 아이디를 갖고 옵니다.
        $user_session = $session->get("user_session");
        $is_login = isset($user_session["user_id"]);

        $current_uri = current_url(true);
        $segments = $current_uri->getSegments();
        $segment0 = isset($segments[0]) == false ? "dashboard" : $segments[0];
        $segment1 = isset($segments[1]) == false  ? "dashboard" : $segments[1];
        $segment_uri = "/".$segment0."/".$segment1;

        // 로그인하지 않아도 되는 url
        $allow_uri = array();
        $allow_uri[] = "/user/login";
        $allow_uri[] = "/user/loginProc";
        $allow_uri[] = "/user/register";
        $allow_uri[] = "/user/registerProc";

        if (in_array($segment_uri, $allow_uri)) { // 로그인 없어도 허용되는 url은
            // 아무것도 하지 않음
        } elseif ($is_login == true) { // 로그인이 필요한 url이고 로그인한 경우
            // 아무것도 하지 않음
        } else { // 로그인 안했으나 로그인이 필요한 url인 경우
            $base_url = base_url(); // 기본 url 입력
            $session->destroy(); // 세션 삭제
            header("Location: $base_url/user/login"); // 로그인 하는 사이트로 보냄
            exit;
        }
    }
}

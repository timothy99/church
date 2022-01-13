<?php namespace App\Models;

use CodeIgniter\Model;
use App\Models\QueryModel;

class AuthorityModel extends Model
{
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
    public function checkUrl()
    {
        $is_check_url = false; // 로그인 필요 여부
        $current_uri = uri_string(); // 현재 주소의 uri갖고 온다

        $current_uri = current_url(true);
        $segments = $current_uri->getSegments();
        $segment0 = isset($segments[0]) == false ? "dashboard" : $segments[0];
        $segment1 = isset($segments[1]) == false  ? "dashboard" : $segments[1];
        $segment_uri = "/".$segment0."/".$segment1;

        // 로그인하지 않아도 되는 url
        $allow_uri = array();
        $allow_uri[] = "/user/login";
        // $allow_uri[] = "/dashboard/dashboard";

        if (in_array($segment_uri, $allow_uri)) {
            $is_check_url = true; // 로그인 없어도 되는 url은 허용
        }

        return $is_check_url;
    }
}

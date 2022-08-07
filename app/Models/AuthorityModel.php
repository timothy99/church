<?php namespace App\Models;

use CodeIgniter\Model;

class AuthorityModel extends Model
{
    /**
     * [Description for checkIp]
     * 전체 사이트에 대한 아이피(ip) 체크
     *
     * @return void
     * 
     * @author     timothy99
     */
    public function checkIp() : void
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

    /**
     * [Description for checkBatch]
     * 일부 uri에 대해서만 ip체크
     *
     * @return void
     * 
     * @author     timothy99
     */
    public function checkBatch() : void
    {
        $ip_address = $_SERVER["REMOTE_ADDR"];
        $current_uri = uri_string();

        $ip_arr = array();
        $ip_arr = explode("|", env("ip.address"));

        // 해당되는 IP인치 체크하는 uri
        $check_uri = array();
        $check_uri[] = "batch/meal";

        $is_url = in_array($current_uri, $check_uri);
        if($is_url == true) {
            $is_ip = in_array($ip_address, $ip_arr);
        }

        $is_ip = in_array($ip_address, $ip_arr);
        if($is_url == true && $is_ip == false) {
            echo "not the type4";
            logMessage("not the type4");
            exit;
        }
    }

    /**
     * [Description for checkLogin]
     * 로그인없이도 접근이 허용되는 url인지 확인한다
     * 
     * @return void
     * 
     * @author     timothy99
     */
    public function checkLogin() : void
    {
        // 세션을 초기화 합니다.
        $session = \Config\Services::session();

        // 세션의 정보중 아이디를 갖고 옵니다.
        $user_session = $session->get("user_session");
        $is_login = isset($user_session->user_id);

        $current_uri = uri_string();

        // 로그인하지 않아도 되는 url
        $allow_uri = array();
        $allow_uri[] = "user/login";
        $allow_uri[] = "user/signin";
        $allow_uri[] = "user/logout";
        $allow_uri[] = "user/forgot";
        $allow_uri[] = "user/register";
        $allow_uri[] = "user/signup";
        $allow_uri[] = "batch/meal";

        if (in_array($current_uri, $allow_uri)) { // 로그인 없어도 허용되는 url은
            // 아무것도 하지 않음
        } elseif ($is_login == true) { // 로그인이 필요한 url이고 로그인한 경우
            /**
             * 항상 회원의 정보를 탑재
             * 세션의 정보는 시간차가 있으므로 관리자 권한을 제외하거나 기타 정보의 변경이 생겼을때 바로 확인 되도록
            */
            $user_idx = $user_session->user_idx;
            $member_model = new MemberModel();
            $member_result = $member_model->getMemberInfo($user_idx);
            $session->set("user_session", $member_result["db_info"]); // 세션에 정보입력
        } else { // 로그인 안했으나 로그인이 필요한 url인 경우
            $base_url = base_url(); // 기본 url 입력
            $session->destroy(); // 세션 삭제
            header("Location: $base_url/user/login"); // 로그인 하는 사이트로 보냄
            exit;
        }
    }
}

<?php

use Config\Database;
use CodeIgniter\HTTP\UserAgent;

//헤더 정보 만들기
function headerInfo()
{
    $user_agent = new UserAgent();

    $device = $user_agent->isMobile() == false ? "PC" : $user_agent->getMobile(); // 모바일 접속여부
    $browser = $user_agent->getBrowser(); // 브라우저
    $version = $user_agent->getVersion(); // 브라우저의 버전
    $referrer = $user_agent->getReferrer(); // 레퍼러
    $platform = $user_agent->getPlatform(); // 플랫폼(윈도우 버전등)
    $ip = $_SERVER["REMOTE_ADDR"]; // 접속IP
    $uri = $_SERVER["REQUEST_URI"]; // 접근한 페이지

    $header_string = "$device|$browser|$version|$platform|$ip|$uri"; // 풀버전
    $header_string = "$ip|$uri"; // 테스트용 단축 버전

    return $header_string;
}

// 로그 남기기
function logMessage($data)
{
    $header_string = headerInfo();
    ob_start();
    print_r($header_string." ---> ");
    print_r($data);
    $data_log = ob_get_clean();
    log_message("error", $data_log);

    return true;
}

// var_dump로 로그 남기기
function logMessageDump($data)
{
    $header_string = headerInfo();
    ob_start();
    print_r($header_string." ---> ");
    var_dump($data);
    $data_log = ob_get_clean();
    log_message("error", $data_log);

    return true;
}

// 쿼리 남기기
function logQuery($data)
{
    $header_string = headerInfo();
    ob_start();
    print_r($header_string." ---> ");
    print_r($data);
    $data_log = ob_get_clean();
    $data_log = str_replace("\n"," ",$data_log);
    log_message("error", $data_log);

    return true;
}

// 가장 마지막 쿼리 로그
function logLastQuery()
{
    $db = Database::connect();
    $last_query = $db->getLastQuery()->getQuery();
    logQuery($last_query);

    return true;
}

// 쿼리 모니터링용 - insert, update, delete 만 기본적으로 로그에 남긴다.
function logModifyQuery()
{
    $db = Database::connect();
    $last_query = $db->getLastQuery()->getQuery();
    $last_query_lower = strtolower($last_query);

    $log_yn = false;
    $insert_position = stripos($last_query_lower, "nsert");
    $update_position = stripos($last_query_lower, "pdate");
    $delete_position = stripos($last_query_lower, "elete");
    $session_position = stripos($last_query_lower, "nit_session");

    if ($insert_position > 0 || $update_position > 0 || $delete_position > 0) {
        $log_yn = true;
    }

    if ($session_position > 0) {
        $log_yn = false;
    }

    if ($log_yn == true) {
        logQuery($last_query);
    }

    return true;
}
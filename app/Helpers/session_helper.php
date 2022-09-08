<?php

// 세션중 사용자가 생성한 세션의 전체 정보 갖고오기
function getUserSession()
{
    // 세션의 정보중 아이디를 갖고 옵니다.
    $session = \Config\Services::session();
    $user_session = $session->get("user_session");

    return $user_session;
}

// 세션중 사용자가 생성한 세션의 지정된 정보 갖고오기
function getUserSessionInfo($info_id)
{
    // 세션의 정보중 아이디를 갖고 옵니다.
    $session = \Config\Services::session();
    $user_session = $session->get("user_session");
    $info_value = $user_session->{$info_id};

    return $info_value;
}

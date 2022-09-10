<?php

// 세션정보 전체 반환
function getSession()
{
    $session = \Config\Services::session();

    return $session;
}

// 세션중 사용자가 생성한 세션의 전체 정보 갖고오기
function getUserSession()
{
    // 세션의 정보중 아이디를 갖고 옵니다.
    $session = getSession();
    $user_session = $session->get("user_session");

    return $user_session;
}

// 세션중 사용자가 생성한 세션의 지정된 정보 갖고오기
function getUserSessionInfo($info_id)
{
    $user_session = getUserSession(); // 세션의 정보중 아이디를 갖고 옵니다.
    $info_value = isset($user_session->{$info_id}) ?? null; // 정보가 없을땐 null 반환

    return $info_value;
}

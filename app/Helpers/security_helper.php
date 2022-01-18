<?php

/**
 * @author 배진모
 * @see 원하는 요청에 따른 랜덤 문자열 생성
 * @param string $method - 랜덤문자열 생성 방식
 * @param string $length - 랜덤문자열 길이
 * @return string $random_string - 랜덤 문자열
 */
function getRandomString($method, $length)
{
    /**
     * 0 - 모든 문자
     * 1 - 숫자만
     * 2 - 대문자만
     * 3 - 소문자만
     * 4 - 숫자+대문자+소문자
     */
    $characters_number = "0123456789";
    $characters_lower = "abcdefghijklmnopqrstuvwxyz";
    $characters_upper = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $characters_etc = "~!@#<>?,.%^&*(_+=-";

    $characters = "";
    if ($method == 0) { // 모든문자
        $characters = $characters_number.$characters_lower.$characters_upper.$characters_etc;
    } elseif ($method == 1) { // 숫자만
        $characters = $characters_number;
    } elseif ($method == 2) { // 대문자만
        $characters = $characters_upper;
    } elseif ($method == 3) { // 소문자만
        $characters = $characters_lower;
    } elseif ($method == 4) { // 숫자+대문자+소문자
        $characters = $characters_number.$characters_lower.$characters_upper;
    }

    $random_string = "";

    while ($length--) {
        $tmp = mt_rand(0, strlen($characters));
        $random_string .= substr($characters, $tmp, 1);
    }

    return $random_string;
}

/**
 * @author 배진모
 * @see 단방향 SHA512방식 암호화 문자열 제공
 * @param string $password - 암호화 이전 평문 문자열
 * @return string $password_enc - 암호화된 문자열
 */
function getPasswordEncrypt($password)
{
    $password_enc = base64_encode(hash("sha512", $password, true)); // 평문인 암호를 암호화 하여 저장

    return $password_enc;
}

/**
 * @author 배진모
 * @see AES256 암호화
 * @param string $text - 암호화 이전 평문 문자열
 * @return string $encrypted - 암호화된 문자열
 */
function getTextEncrypt($text)
{
    $encryption_key = env("encryption.key", null);
    $encryption_iv = env("encryption.iv", null);
    $encryption_way = env("encryption.way", null);

    $encrypted = @openssl_encrypt($text, $encryption_way, $encryption_key, true, $encryption_iv); // 평문인 암호를 암호화 하여 저장
    $encrypted = base64_encode($encrypted);

    return $encrypted;
}

/**
 * @author 배진모
 * @see AES256 복호화
 * @param string $encrypted - 암호화된 문자열
 * @return array $text - 복호화된 문자열
 */
function getTextDecrypt($encrypted)
{
    $encryption_key = env("encryption.key", null);
    $encryption_iv = env("encryption.iv", null);
    $encryption_way = env("encryption.way", null);

    $encrypted = base64_decode($encrypted);
    $text = @openssl_decrypt($encrypted, $encryption_way, $encryption_key, true, $encryption_iv); // 복호화

    return $text;
}

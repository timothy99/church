<?php namespace App\Models;

use CodeIgniter\Model;

class MessageModel extends Model
{
    // 이메일 보내기
    public function sendEmail($from, $from_name, $to, $title, $contents)
    {
        $email = \Config\Services::email(); // 이메일 서비스 로드

        $config["protocol"] = "smtp"; // 전송방식
        $config["SMTPHost"] = env("email.smtp.host"); // 호스트
        $config["SMTPUser"] = env("email.smtp.user"); // 사용자 정보
        $config["SMTPPass"] = env("email.smtp.pass"); // 암호
        $config["SMTPPort"] = env("email.smtp.port"); // 포트

        $email->initialize($config); // config 에 따른 메일 서비스 초기화

        $email->setFrom($from, $from_name); // 보내는 사람 이름과 메일주소
        $email->setTo($to); // 받는 사람 메일 주소

        $email->setSubject($title); // 제목
        $email->setMessage($contents); // 내용

        $email->send(); // 발송
    }

}

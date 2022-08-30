<?php namespace App\Models;
use CodeIgniter\Model;
class MessageModel extends Model
{
    // 이메일 발송기능, 구글 정책 변경으로 인하여 수정 개발 필요
    public function sendEmail($from, $from_name, $to, $title, $contents) : void
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

    // 텔레그램으로데이터 보내기
    public function sendTelegram(string $chat_id, string $message) : void
    {
        $bot_host = env("telegram.bot.host");
        $bot_id = env("telegram.bot.id");

        $telegram_url = $bot_host."/".$bot_id."/sendmessage?chat_id=".$chat_id."&user=1&pass=2&phone=3&text=".$message;

        $ch = curl_init(); // curl 초기화
        $headers = array(); //헤더정보
        array_push($headers, "cache-control: no-cache");
        array_push($headers, "content-type: application/json; charset=utf-8");

        curl_setopt($ch,CURLOPT_URL, $telegram_url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
        curl_setopt($ch,CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_exec($ch);
        curl_close($ch);
    }



    /**
     * [Description for sendTeamRoom]
     *
     * @param string $url_code 함수내에 정의된 url을 불러오기 위한 코드
     * @param string $message
     * 
     * @return bool
     * 
     * @author     timothy99 
     */
    public function sendTeamRoom(string $url_code, string $message) : bool
    {
        $teamroom_host = env("nateon.teamroom.webhook.api.url.".$url_code);

        $payload = array();
        $payload["content"] = $message;
        $payload = http_build_query($payload, "", "&");

        $ch = curl_init(); // curl 초기화
        $headers = array(); //헤더정보
        array_push($headers, "cache-control: no-cache");
        array_push($headers, "application/x-www-form-urlencoded");

        curl_setopt($ch,CURLOPT_URL, $teamroom_host);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
        curl_setopt($ch,CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        logMessage("네이트온 팀룸 웹훅 결과 : ".$result);

        return $result;
    }

}
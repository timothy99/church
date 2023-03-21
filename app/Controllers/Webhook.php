<?php namespace App\Controllers;

use App\Models\MessageModel;

class Webhook extends BaseController
{
    // 깃허브에서 오는거 적당히 손질해서 텔레그램으로 보내주기
    public function github()
    {
        $message_model = new MessageModel();

        $type = $this->request->header("X-Github-Event")->getValue();
        $body = json_decode($this->request->getBody());

        $telegram = null;
        if ($type == "issues") {
            $action = $body->action;
            $issue = $body->issue;
            $url = $issue->html_url;
            $title = $issue->title;
            $contents = mb_substr(str_replace("\r\n", "", $issue->body), 0, 40);

            $telegram = $type." | ".$action." | ".$url." | ".$title." | ".$contents;
        } else if ($type == "push") {
            $title = null;
            $commits = $body->commits;
            foreach($commits as $no => $val) {
                $message = $val->message;
                $commit_no = $no+1;
                $title = $title.$commit_no.". ".$message."\n";
            }
            $telegram = $type." | ".$title;
        } else {
            $telegram = $type." | 새로운 형식. 로그를 확인해주세요.";
        }

        $chat_id = "83796674";
        $message_model->sendTelegram($chat_id, $telegram);
    }

    // 출석체크 이벤트 알림
    public function attendance()
    {
        $message_model = new MessageModel();
        $message = "전남 / 옹진 / 거창 출석 체크 해주세요~!!";
        $model_result = $message_model->sendTeamRoom("N03", $message);
    }

}

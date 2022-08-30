<?php namespace App\Controllers;

use App\Models\MessageModel;
use App\Models\MealModel;

class Batch extends BaseController
{
    // 오늘의 구내식당
    public function meal()
    {
        $today = date("Y-m-d");

        $meal_model = new MealModel();
        $message_model = new MessageModel();

        $model_result = $meal_model->getInfo($today);
        $meal_info = $model_result["db_info"];
        
        if($meal_info == null) {
            logMessage("오늘은 구내식당 메뉴가 없네요");
        } else {
            $message = $meal_info->meal_menu;
            $model_result = $message_model->sendTeamRoom("N01", $message);
        }
    }

    // 출석체크 이벤트 알림
    public function attendance()
    {
        $message_model = new MessageModel();
        $message = "전남 / 옹진 / 거창 출석 체크 해주세요~!!";
        $model_result = $message_model->sendTeamRoom("N03", $message);
    }

}

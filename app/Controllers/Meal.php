<?php

namespace App\Controllers;

use App\Models\MealModel;
use stdClass;

class Meal extends BaseController
{
    public function list()
    {
        $view = view("meal/list");
        echo $view;
    }

    public function month()
    {
        $start = $this->request->getPost("start");
        $end = $this->request->getPost("end");

        $start_date = date_format(date_create($start), "Y-m-d");
        $end_date = date_format(date_create($end), "Y-m-d");

        $meal_data = array();
        $meal_data["start_date"] = $start_date;
        $meal_data["end_date"] = $end_date;

        $meal_model = new MealModel();
        $db_result = $meal_model->getList($meal_data);
        $meal_list = $db_result["db_list"];

        echo json_encode($meal_list);
    }

    public function view()
    {
        $meal_date = $this->request->uri->getSegment(3);
        $meal_model = new MealModel();
        $db_result = $meal_model->getInfo($meal_date);
        $meal_info = $db_result["db_info"];

        $proc_result = array();
        $proc_result["meal_info"] = $meal_info;
        $proc_result["meal_date"] = $meal_date;
        if($meal_info == null) {
            $meal_info = new stdClass();
            $meal_info->meal_menu = null;
            $proc_result["meal_info"] = $meal_info;

            $view_file = "meal/edit";
        } else {
            $ins_date = date_create_from_format("YmdHis", $meal_info->ins_date);
            $ins_date_txt = $ins_date->format("Y-m-d H:i:s");
            $meal_info->ins_date_txt = $ins_date_txt;
            $meal_info->meal_menu_txt = nl2br($meal_info->meal_menu);

            $view_file = "meal/view";
        }

        echo view($view_file, $proc_result);
    }

    public function edit()
    {
        $meal_date = $this->request->uri->getSegment(3);
        $meal_model = new MealModel();
        $db_result = $meal_model->getInfo($meal_date);
        $meal_info = $db_result["db_info"];

        $proc_result = array();
        $proc_result["meal_info"] = $meal_info;
        $proc_result["meal_date"] = $meal_date;
        $view = view("meal/edit", $proc_result);

        echo $view;
    }

    public function update()
    {
        $meal_date = $this->request->getPost("meal_date", FILTER_SANITIZE_SPECIAL_CHARS);
        $meal_menu = $this->request->getPost("meal_menu");

        $meal_data = array();
        $meal_data["meal_date"] = $meal_date;
        $meal_data["meal_menu"] = $meal_menu;

        $meal_model = new MealModel();
        $model_result = $meal_model->getInfo($meal_date);
        $meal_info = $model_result["db_info"];
        if($meal_info == null) {
            $model_result = $meal_model->procInsert($meal_data);
        } else {
            $model_result = $meal_model->procUpdate($meal_data);
        }
        
        echo json_encode($model_result);
    }

    public function delete()
    {
        $meal_date = $this->request->getPost("meal_date", FILTER_SANITIZE_SPECIAL_CHARS);

        $meal_model = new MealModel();
        $model_result = $meal_model->procDelete($meal_date);

        echo json_encode($model_result);
    }

}

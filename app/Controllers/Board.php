<?php

namespace App\Controllers;

use App\Models\BoardModel;
use App\Models\PagingModel;
use stdClass;

class Board extends BaseController
{
    public function list()
    {
        $board_model = new BoardModel();
        $paging_model = new PagingModel();

        $rows = 10;
        $page = $this->request->getGet("p") ?? 1;
        $search_text = $this->request->getGet("q", FILTER_SANITIZE_SPECIAL_CHARS);
        $model_result = $board_model->getBoardList($page, $rows, $search_text);

        $cnt = $model_result["db_cnt"]; // 데이터 총합
        $paging = $paging_model->getPaging($page, $rows, $cnt);
        $paging_view = view("paging/paging", ["paging"=>$paging, "q"=>$search_text, "href_link"=>"/board/list"]); // 페이징 뷰

        $proc_result = array();
        $proc_result["result"] = $model_result["result"];
        $proc_result["message"] = $model_result["message"];
        $proc_result["meal_list"] = $model_result["db_list"];
        $proc_result["cnt"] = $cnt;
        $proc_result["paging"] = $paging;
        $proc_result["start_row"] = ($page-1)*$rows+1;
        $proc_result["p"] = $page;
        $proc_result["q"] = $search_text;
        $proc_result["paging_view"] = $paging_view; // 페이징 뷰

        $view = view("board/list", $proc_result);
        echo $view;
    }

    public function view()
    {
        // 내용화면
    }

    public function edit()
    {
        // 수정화면
    }

    public function insert()
    {
        // 입력 로직
    }

    public function update()
    {
        // 수정 로직
    }

    public function delete()
    {
        // 삭제 로직
    }

}

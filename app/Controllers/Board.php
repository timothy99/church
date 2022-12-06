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
        $proc_result["board_list"] = $model_result["db_list"];
        $proc_result["cnt"] = $cnt;
        $proc_result["paging"] = $paging;
        $proc_result["start_row"] = ($page-1)*$rows;
        $proc_result["p"] = $page;
        $proc_result["q"] = $search_text;
        $proc_result["paging_view"] = $paging_view; // 페이징 뷰

        $view = view("board/list", $proc_result);
        return $view;
    }

    public function view()
    {
        $board_model = new BoardModel();

        $board_idx = $this->request->getUri()->getSegment(3);

        $board_info = $board_model->getBoardInfo($board_idx);

        $view = view("board/view", $board_info);
        return $view;
    }

    public function write()
    {
        // 글쓰기는 아무 데이터가 없으니까 빈값을 넘긴다
        $board_info = new stdClass();
        $board_info->board_idx = 0;
        $board_info->title = null;
        $board_info->contents = null;
        $board_info->http_link = null;
        $board_info->notice_checked = null;
        $board_info->secret_checked = null;

        $data = array();
        $data["href_action"] = "/board/insert";
        $data["board_info"] = $board_info;
        $view = view("board/edit", $data);

        return $view;
    }

    public function edit()
    {
        $board_model = new BoardModel();

        $board_idx = $this->request->getUri()->getSegment(3);

        $model_result = $board_model->getBoardInfo($board_idx);

        $data = array();
        $data["href_action"] = "/board/update";
        $data["board_info"] = $model_result["board_info"];
        $view = view("board/edit", $data);

        return $view;
    }

    public function insert()
    {
        $board_model = new BoardModel();

        $result = true;
        $message = "입력이 잘 되었습니다.";

        $notice_yn = $this->request->getPost("notice_yn") ?? "N";
        $secret_yn = $this->request->getPost("secret_yn") ?? "N";
        $title = $this->request->getPost("title", FILTER_SANITIZE_SPECIAL_CHARS);
        $contents = $this->request->getPost("contents", FILTER_SANITIZE_SPECIAL_CHARS);
        $http_link = $this->request->getPost("http_link", FILTER_SANITIZE_URL);

        if ($title == null) {
            $result = false;
            $message = "제목을 입력해주세요";
        }

        if ($contents == null) {
            $result = false;
            $message = "내용을 입력해주세요";
        }

        if ($result == true) {
            $board_data = array();
            $board_data["notice_yn"] = $notice_yn;
            $board_data["secret_yn"] = $secret_yn;
            $board_data["title"] = $title;
            $board_data["contents"] = $contents;
            $board_data["http_link"] = $http_link;

            $proc_result = $board_model->insertBoard($board_data);
        } else {
            $proc_result = array();
            $proc_result["result"] = $result;
            $proc_result["message"] = $message;
        }

        return json_encode($proc_result);
    }

    public function update()
    {
        $board_model = new BoardModel();

        $result = true;
        $message = "입력이 잘 되었습니다.";

        $board_idx = $this->request->getPost("board_idx", FILTER_SANITIZE_SPECIAL_CHARS);
        $notice_yn = $this->request->getPost("notice_yn") ?? "N";
        $secret_yn = $this->request->getPost("secret_yn") ?? "N";
        $title = $this->request->getPost("title", FILTER_SANITIZE_SPECIAL_CHARS);
        $contents = $this->request->getPost("contents", FILTER_SANITIZE_SPECIAL_CHARS);
        $http_link = $this->request->getPost("http_link", FILTER_SANITIZE_URL);

        if ($title == null) {
            $result = false;
            $message = "제목을 입력해주세요";
        }

        if ($contents == null) {
            $result = false;
            $message = "내용을 입력해주세요";
        }

        if ($result == true) {
            $board_data = array();
            $board_data["board_idx"] = $board_idx;
            $board_data["notice_yn"] = $notice_yn;
            $board_data["secret_yn"] = $secret_yn;
            $board_data["title"] = $title;
            $board_data["contents"] = $contents;
            $board_data["http_link"] = $http_link;

            $proc_result = $board_model->updateBoard($board_data);
        } else {
            $proc_result = array();
            $proc_result["result"] = $result;
            $proc_result["message"] = $message;
        }

        return json_encode($proc_result);
    }

    public function delete()
    {
        $board_model = new BoardModel();

        $board_idx = $this->request->getPost("board_idx", FILTER_SANITIZE_SPECIAL_CHARS);
        $proc_result = $board_model->deleteBoard($board_idx);

        return json_encode($proc_result);
    }
}

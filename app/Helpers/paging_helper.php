<?php

// 페이징 생성
function getPaging($page, $rows, $cnt)
{
    $page_arr = array();

    $prev_pages = 4; // 현재 페이지($page)기준 앞에 있을 최대 페이지 수
    $next_pages = 4; // 현재 페이지($page)기준 뒤에 있을 최대 페이지 수

    $max_page = ceil($cnt/$rows); // 총 합 기준 가장 마지막 페이지

    $start_page = $page-$prev_pages < 1 ? 1 : $page-$prev_pages; // 현재 페이지에서 앞에 있을 페이지를 빼서 1보다 작으면 1로 고정
    $end_page = $page+$next_pages > $max_page ? $max_page : $page+$next_pages; // 현재 페이지에서 마지막 페이지를 더해 크면 마지막 페이지로 고정

    if($page < 5) {
        $end_page = 9;
    }

    if($end_page-$start_page < 8) {
        $start_page = $end_page-8;
    }

    if($max_page < $end_page) {
        $end_page = $max_page;
    }

    $page_arr = array();
    for($i = $start_page; $i <= $end_page; $i++) {
        $active_class = "";
        if($i == $page) {
            $active_class = "active";
        }
        $page_arr[] = ["page_num"=>$i, "active_class"=>$active_class];
    }

    $paging = array();
    $paging["page"] = $page;
    $paging["start_page"] = $start_page;
    $paging["end_page"] = $end_page;
    $paging["max_page"] = $max_page;
    $paging["cnt"] = $cnt;
    $paging["page_arr"] = $page_arr;

    return $paging;
}

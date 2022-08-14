                                <ul class="pagination pagination-sm m-0 float-right">
                                    <li class="page-item"><a class="page-link" href="/member/list?p=1&q=<?=$q ?>">&laquo;</a></li>
                                    <li class="page-item"><a class="page-link" href="/member/list?p=<?=$paging["start_page"] ?>&q=<?=$q ?>">&lt;</a></li>
<?php
    foreach ($paging["page_arr"] as $no => $val) :
?>
                                    <li class="page-item <?=$val["active_class"] ?>">
                                        <a class="page-link" href="/member/list?p=<?=$val["page_num"] ?>&q=<?=$q ?>"><?=$val["page_num"] ?></a>
                                    </li>
<?php
    endforeach;
?>
                                    <li class="page-item"><a class="page-link" href="/member/list?p=<?=$paging["end_page"] ?>&q=<?=$q ?>">&gt;</a></li>
                                    <li class="page-item"><a class="page-link" href="/member/list?p=<?=$paging["max_page"] ?>&q=<?=$q ?>">&raquo;</a></li>
                                </ul>
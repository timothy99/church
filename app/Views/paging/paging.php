                                <ul class="pagination pagination-sm m-0">
                                    <li class="page-item"><a class="page-link" href="<?=$href_link ?>?p=1&q=<?=$q ?>">&laquo;</a></li>
                                    <li class="page-item"><a class="page-link" href="<?=$href_link ?>?p=<?=$paging["start_page"] ?>&q=<?=$q ?>">&lt;</a></li>
<?php
    foreach ($paging["page_arr"] as $no => $val) :
?>
                                    <li class="page-item <?=$val["active_class"] ?>">
                                        <a class="page-link" href="<?=$href_link ?>?p=<?=$val["page_num"] ?>&q=<?=$q ?>"><?=$val["page_num"] ?></a>
                                    </li>
<?php
    endforeach;
?>
                                    <li class="page-item"><a class="page-link" href="<?=$href_link ?>?p=<?=$paging["end_page"] ?>&q=<?=$q ?>">&gt;</a></li>
                                    <li class="page-item"><a class="page-link" href="<?=$href_link ?>?p=<?=$paging["max_page"] ?>&q=<?=$q ?>">&raquo;</a></li>
                                </ul>
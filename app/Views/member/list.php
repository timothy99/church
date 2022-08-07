<?php   include_once APPPATH."Views/include/header.php"; // 헤더 ?>
<?php   include_once APPPATH."Views/include/menu.php"; // 네비게이션(상단, 좌측 메뉴) ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">회원목록</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">홈</a></li>
                            <li class="breadcrumb-item active">회원목록</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div><!-- /.content-header -->

        <section class="content"><!-- Main content -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">총 <?=number_format($cnt) ?>건</h3>
                                <div class="card-tools">
                                    <div class="input-group input-group-sm">
                                        <input type="text" id="q" name="q" class="form-control float-right" placeholder="검색어를 입력하세요" value="<?=$q ?>">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-default" id="q_search" name="q_search">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered table-hover">
                                    <thead class="text-center">
                                        <tr>
                                            <th>#</th>
                                            <th>아이디</th>
                                            <th>이름</th>
                                            <th>등록일</th>
                                            <th>등록자</th>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php
    foreach ($user_list as $no => $val) :
?>
                                        <tr>
                                            <td class="text-center"><?=$start_row+$no ?></td>
                                            <td><a href="/member/view/<?=$val->user_idx ?>"><?=$val->user_id ?></a></td>
                                            <td><a href="/member/view/<?=$val->user_idx ?>"><?=$val->user_name ?></a></td>
                                            <td><?=$val->ins_date ?></td>
                                            <td><?=$val->ins_id ?></td>
                                        </tr>
<?php
    endforeach;
?>
<?php
    if (count($user_list) == 0) :
?>
                                        <tr>
                                            <td colspan="5" class="text-center">데이터가 없습니다</td>
                                        </tr>
<?php
    endif;
?>

                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer clearfix">
<?=$paging_view ?>
                            </div>
                        </div><!-- /.card -->
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

<?php   include_once APPPATH."Views/include/footer.php"; // 하단 ?>

<script>
    $(function() {
        $("#q").keydown(function(e) {
            if(e.keyCode == 13) {
                search();
            }
            
        });

        $("#q_search").click(function(e) {
            search();
        });
    });

    function search() {
        var q = $("#q").val();
        location.href = "/member/list?p=1&q="+q;
    }

    $(window).on("load", function() {
        $("#upper-member-list").addClass("menu-open");
        $("#a-member-list").addClass("active");
        $("#bottom-member-list").addClass("active");
    });
</script>
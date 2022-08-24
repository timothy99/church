<?php   include_once APPPATH."Views/include/header.php"; // 헤더 ?>

<?php   include_once APPPATH."Views/include/menu.php"; // 네비게이션(상단, 좌측 메뉴) ?>

<input type="hidden" id="meal_date" name="meal_date" value="<?=$meal_date ?>">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">구내식당</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">홈</a></li>
                            <li class="breadcrumb-item">구내식당</li>
                            <li class="breadcrumb-item active">식단보기</li>
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
                                <h3 class="card-title"><?=$meal_date ?> 의 식단정보입니다.</h3>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                        <tr>
                                            <th>날짜</th>
                                            <td><?=$meal_info->meal_date ?></td>
                                        </tr>
                                        <tr>
                                            <th>식단</th>
                                            <td><?=$meal_info->meal_menu_txt ?></td>
                                        </tr>
                                        <tr>
                                            <th>등록자</th>
                                            <td><?=$meal_info->ins_id ?></td>
                                        </tr>
                                        <tr>
                                            <th>등록일</th>
                                            <td><?=$meal_info->ins_date_txt ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer clearfix">
                                <div class="float-right">
                                    <button class="btn btn-info" id="edit" name="edit">수정</button>
                                    <button class="btn btn-danger" id="delete" name="delete">삭제</button>
                                </div>
                            </div>
                        </div><!-- /.card -->
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

<?php   include_once APPPATH."Views/include/footer.php"; // 하단 ?>

<script>
    // 좌측 메뉴 강조하는 함수
    $(window).on("load", function() {
        $("#upper-meal-list").addClass("menu-open");
        $("#a-meal-list").addClass("active");
        $("#bottom-meal-list").addClass("active");
    });

    $(function() {
        $("#edit").click(function(e) {
            var meal_date = $("#meal_date").val();
            location.href = "/meal/edit/"+meal_date;
        });

        $("#delete").click(function(e) {
            var meal_date = $("#meal_date").val();
            if(confirm("삭제하시겠습니까?")) {
                $.ajax({
                    url: "/meal/delete",
                    type: "post",
                    dataType: "json",
                    data: {meal_date : meal_date},
                    success: function(proc_result) {
                        var result = proc_result.result;
                        var message = proc_result.message;
                        alert(message);
                        if(result == true) {
                            location.href = "/meal/list";
                        }
                    }
                });
            }
        });
    });
</script>
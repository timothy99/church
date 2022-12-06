<?php   include_once APPPATH."Views/include/header.php"; // 헤더 ?>
<?php   include_once APPPATH."Views/include/menu.php"; // 네비게이션(상단, 좌측 메뉴) ?>

<form id="frm" name="frm">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">식단 수정</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">홈</a></li>
                            <li class="breadcrumb-item">구내식당</li>
                            <li class="breadcrumb-item active">식단 수정</li>
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
                                <h3 class="card-title"><?=$meal_date ?> 의 식단정보를 입력합니다</h3>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                        <tr>
                                            <th>날짜</th>
                                            <td>
                                                <div class="form-group">
                                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                                        <input type="text" class="form-control datetimepicker-input" id="meal_date" name="meal_date" data-target="#reservationdate" data-toggle="datetimepicker" readonly value="<?=$meal_date ?>" />
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>식단</th>
                                            <td>
                                                <textarea class="form-control" id="meal_menu" name="meal_menu" rows="6"><?=$meal_info->meal_menu ?></textarea>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer clearfix">
                                <div class="float-right">
                                    <input type="button" class="btn btn-info" id="meal_save" name="meal_save" value="저장">
                                    <input type="button" class="btn btn-danger" id="meal_cancel" name="meal_cancel" value="취소">
                                </div>
                            </div>
                        </div><!-- /.card -->
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
</form>

<?php   include_once APPPATH."Views/include/footer.php"; // 하단 ?>

<script>
    // 좌측 메뉴 강조하는 함수
    $(window).on("load", function() {
        $("#upper-meal-calendar").addClass("menu-open");
        $("#a-meal-calendar").addClass("active");
        $("#bottom-meal-calendar").addClass("active");
    });

    $(function() {
        $("#meal_save").click(function(e) {
            $.ajax({
                url: "/meal/update",
                type: "POST",
                dataType: "json",
                data: $("#frm").serialize(),
                success: function(proc_result) {
                    var result = proc_result.result;
                    var message = proc_result.message;
                    if(result == true) {
                        location.href = "/meal/calendar";
                    } else {
                        alert(message);
                    }
                }
            });
        });
        $("#meal_cancel").click(function(e) {
            history.go(-1);
        });
    });

    //Date picker
    $("#reservationdate").datetimepicker({
        format: "YYYY-MM-DD"
    });

    $("#reservationdate").attr("readonly",true);
</script>
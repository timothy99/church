<?php   include_once APPPATH."Views/include/header.php"; // 헤더 ?>
<?php   include_once APPPATH."Views/include/menu.php"; // 네비게이션(상단, 좌측 메뉴) ?>

<form id="frm" name="frm">
<input type="hidden" id="user_idx" name="user_idx" value="<?=$user_info->user_idx ?>">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">회원상세</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">홈</a></li>
                            <li class="breadcrumb-item active">회원상세</li>
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
                                <h3 class="card-title"><?=$user_info->user_name ?> 회원의 상세정보 입니다.</h3>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                        <tr>
                                            <th>아이디</th>
                                            <td><?=$user_info->user_id ?></td>
                                        </tr>
                                        <tr>
                                            <th>이름</th>
                                            <td><input type="text" class="form-control" id="user_name" name="user_name" value="<?=$user_info->user_name ?>"></td>
                                        </tr>
                                        <tr>
                                            <th>관리자 여부</th>
                                            <td>
                                                <select class="form-control" id="admin_yn" name="admin_yn">
                                                    <option value="N" <?php if($user_info->admin_yn == "N") echo "selected"; ?>>N</option>
                                                    <option value="Y" <?php if($user_info->admin_yn == "Y") echo "selected"; ?>>Y</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>사용 여부</th>
                                            <td>
                                                <select class="form-control" id="use_yn" name="use_yn">
                                                    <option value="N" <?php if($user_info->use_yn == "N") echo "selected"; ?>>N</option>
                                                    <option value="Y" <?php if($user_info->use_yn == "Y") echo "selected"; ?>>Y</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer clearfix">
                                <div class="float-right">
                                    <input type="button" class="btn btn-info" id="user_save" name="user_save" value="저장">
                                    <input type="button" class="btn btn-danger" id="user_cancel" name="user_cancel" value="취소">
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
    $(function() {
        $("#user_save").click(function(e) {
            $.ajax({
                url: "/member/update",
                type: "POST",
                dataType: "json",
                data: $("#frm").serialize(),
                success: function(proc_result) {
                    var result = proc_result.result;
                    var message = proc_result.message;
                    alert(message);
                    if(result == true) {
                        location.href = "/member/view/<?=$user_info->user_idx ?>";
                    }
                }
            });
        });
    });

    $(window).on("load", function() {
        $("#upper-member-list").addClass("menu-open");
        $("#a-member-list").addClass("active");
        $("#bottom-member-list").addClass("active");
    });
</script>
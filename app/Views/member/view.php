<?php   include_once APPPATH."Views/include/header.php"; // 헤더 ?>
<?php   include_once APPPATH."Views/include/menu.php"; // 네비게이션(상단, 좌측 메뉴) ?>

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
                                            <td><?=$user_info->user_name ?></td>
                                        </tr>
                                        <tr>
                                            <th>프로필 이미지</th>
                                            <td><img src="<?=$user_info->profile_image_base64 ?>"></td>
                                        </tr>
                                        <tr>
                                            <th>관리자 여부</th>
                                            <td><?=$user_info->admin_yn ?></td>
                                        </tr>
                                        <tr>
                                            <th>사용 여부</th>
                                            <td><?=$user_info->use_yn ?></td>
                                        </tr>
                                        <tr>
                                            <th>등록일</th>
                                            <td><?=$user_info->ins_date ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer clearfix">
                                <div class="float-right">
                                    <button class="btn btn-warning" id="user_list" name="user_list">목록</button>
                                    <button class="btn btn-info" id="user_edit" name="user_edit">수정</button>
                                    <button class="btn btn-danger" id="user_delete" name="user_delete">삭제</button>
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
        $("#upper-member-list").addClass("menu-open");
        $("#a-member-list").addClass("active");
        $("#bottom-member-list").addClass("active");
    });

    $(function() {
        $("#user_edit").click(function(e) {
            var user_idx = $("#user_idx").val();
            location.href = "/member/edit/"+user_idx;
        });

        $("#user_list").click(function(e) {
            location.href = "/member/list/";
        });

        $("#user_delete").click(function(e) {
            var user_idx = $("#user_idx").val();
            if(confirm("삭제하시겠습니까?")) {
                $.ajax({
                    url: "/member/delete",
                    type: "post",
                    dataType: "json",
                    data: {user_idx : user_idx},
                    success: function(proc_result) {
                        var result = proc_result.result;
                        var message = proc_result.message;
                        alert(message);
                        if(result == true) {
                            location.href = "/user/userList";
                        }
                    }
                });
            }
        });
    });

</script>
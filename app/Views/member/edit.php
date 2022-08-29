<?php   include_once APPPATH."Views/include/header.php"; // 헤더 ?>
<?php   include_once APPPATH."Views/include/menu.php"; // 네비게이션(상단, 좌측 메뉴) ?>

<form id="frm" name="frm">
<input type="hidden" id="user_idx" name="user_idx" value="<?=$member_info->user_idx ?>">
<input type="hidden" id="admin_yn_hidden" name="admin_yn" value="<?=$member_info->admin_yn ?>">
<input type="hidden" id="use_yn_hidden" name="use_yn" value="<?=$member_info->use_yn ?>">
<input type="hidden" id="profile_image" name="profile_image" value="<?=$member_info->profile_image ?>">

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
                                <h3 class="card-title"><?=$member_info->user_name ?> 회원의 상세정보 입니다.</h3>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                        <tr>
                                            <th>아이디</th>
                                            <td><?=$member_info->user_id ?></td>
                                        </tr>
                                        <tr>
                                            <th>이름</th>
                                            <td><input type="text" class="form-control" id="user_name" name="user_name" value="<?=$member_info->user_name ?>"></td>
                                        </tr>
                                        <tr>
                                            <th>프로필 이미지</th>
                                            <td>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" id="profile_image_file">
                                                            <label class="custom-file-label" for="profile_image">업로드할 파일을 선택하세요</label>
                                                        </div>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">올리기</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>기존 프로필 이미지</th>
                                            <td id="uploaded_profile_image"><img src="<?=$member_info->profile_image_base64_html ?>"></td>
                                        </tr>
                                        <tr>
                                            <th>관리자 여부</th>
                                            <td>
                                                <select class="form-control" id="admin_yn" name="admin_yn">
                                                    <option value="N">N</option>
                                                    <option value="Y">Y</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>사용 여부</th>
                                            <td>
                                                <select class="form-control" id="use_yn" name="use_yn">
                                                    <option value="N">N</option>
                                                    <option value="Y">Y</option>
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
    // 좌측 메뉴 강조하는 함수
    $(window).on("load", function() {
        $("#upper-member-list").addClass("menu-open");
        $("#a-member-list").addClass("active");
        $("#bottom-member-list").addClass("active");
    });

    // 셀렉트 박스 값 강조
    $("#admin_yn").val($("#admin_yn_hidden").val()).prop("selected", true);
    $("#use_yn").val($("#use_yn_hidden").val()).prop("selected", true);

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
                        location.href = "/member/view/<?=$member_info->user_idx ?>";
                    }
                }
            });
        });

        $("#profile_image_file").change(function(e) {
            var formData = new FormData();
            var request_input = $("#profile_image_file")[0];
            formData.append("request_input", request_input.files[0]);

            $.ajax({
                url: "/upload/profile",
                type: "POST",
                dataType: "json",
                data: formData,
                processData: false,
                contentType: false,
                success: function(proc_result) {
                    var message = proc_result.message;
                    var result = proc_result.result;
                    var image_base64 = proc_result.image_base64;
                    var profile_image = proc_result.profile_image;
                    if(result == true) {
                        document.getElementById("uploaded_profile_image").innerHTML = "<img src=\""+image_base64+"\">";
                        document.getElementById("profile_image").value = profile_image;
                    } else {
                        alert(message);
                    }
                }
            });
        });
    });


</script>

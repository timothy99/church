<?php   include_once APPPATH."Views/include/header.php"; // 헤더 ?>
<?php   include_once APPPATH."Views/include/menu.php"; // 네비게이션(상단, 좌측 메뉴) ?>

<form id="frm" name="frm" class="form-horizontal" data-bitwarden-watching="1">
    <input type="hidden" id="board_idx" name="board_idx" value="<?=$board_info->board_idx ?>">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">글쓰기</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">홈</a></li>
                            <li class="breadcrumb-item">게시판</li>
                            <li class="breadcrumb-item active">글쓰기</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div><!-- /.content-header -->

        <section class="content"><!-- Main content -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">글쓰기</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="offset-sm-2 col-sm-1">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="notice_yn" name="notice_yn" value="Y" <?=$board_info->notice_checked ?>>
                                            <label class="form-check-label" for="notice_yn">공지</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="secret_yn" name="secret_yn" value="Y" <?=$board_info->secret_checked ?>>
                                            <label class="form-check-label" for="secret_yn">비밀글</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label">제목</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="title" name="title" placeholder="제목을 입력하세요" value="<?=$board_info->title ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label">내용</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="contents" name="contents" rows="6"><?=$board_info->contents ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label">링크</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="http_link" name="http_link" placeholder="링크를 입력하세요" value="<?=$board_info->http_link ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-danger" id="board_cancel" name="board_cancel">취소</button>
                                <button type="button" class="btn btn-info float-right" id="board_save" name="board_save">저장</button>
                            </div>
                        </div>
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
        $("#upper-board-list").addClass("menu-open");
        $("#a-board-list").addClass("active");
        $("#bottom-board-list").addClass("active");
    });

    $(function() {
        $("#board_save").click(function(e) {
            $.ajax({
                url: "<?=$href_action ?>",
                type: "POST",
                dataType: "json",
                data: $("#frm").serialize(),
                success: function(proc_result) {
                    var result = proc_result.result;
                    var message = proc_result.message;
                    if(result == true) {
                        location.href = "/board/list";
                    } else {
                        alert(message);
                    }
                }
            });
        });

        $("#board_cancel").click(function(e) {
            history.go(-1);
        });

        // 썸머노트 초기화
        $("#contents").summernote({
            height: 300, // 높이
            focus: true, // 로딩후 포커스 이동
            lang: "ko-KR", // 언어파일
            callbacks : { 
                onImageUpload : function(files) { // 파일 업로드(다중업로드를 위해 반복문 사용)
                    for (var i = files.length-1; i >= 0; i--) {
                        uploadSummernoteFile(files[i]);
                    }
                }
            }
        }); // Summernote
    });

    // 썸머노트 파일 첨부 로직
    function uploadSummernoteFile(file) {
        formData = new FormData();
        formData.append("file", file);
        $.ajax({
            data : formData,
            type : "POST",
            url : "/attach/board",
            dataType: "json",
            processData : false,
            contentType : false,
            success : function(proc_result) {
                var file_html = proc_result.file_html;
                $("#contents").summernote("pasteHTML", file_html);
            }
        });
    }
</script>

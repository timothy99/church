<?php   include_once APPPATH."Views/include/header.php"; // 헤더 ?>
<?php   include_once APPPATH."Views/include/menu.php"; // 네비게이션(상단, 좌측 메뉴) ?>

<form id="frm" name="frm" class="form-horizontal" data-bitwarden-watching="1">
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
                                <h3 class="card-title">Horizontal Form</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="offset-sm-2 col-sm-10">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="exampleCheck2">
                                            <label class="form-check-label" for="exampleCheck2">공지</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label">제목</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="title" placeholder="제목을 입력하세요" value="<?=$board_info->title ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label">내용</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="contents" name="contents" rows="6"><?=$board_info->contents ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-info" id="board_save" name="board_save">저장</button>
                                <button type="button" class="btn btn-default float-right" id="board_cancel" name="board_cancel">취소</button>
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
                        location.href = "/meal/list";
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

</script>
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
                        <h1 class="m-0">글보기</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">홈</a></li>
                            <li class="breadcrumb-item">게시판</li>
                            <li class="breadcrumb-item active">글보기</li>
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
                                <h3 class="card-title"><?=$board_info->title ?></h3>
                            </div><!-- /.card-header -->
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-2">공지</dt>
                                <dd class="col-4"><?=$board_info->notice_yn ?></dd>
                                <dt class="col-2">비밀글</dt>
                                <dd class="col-4"><?=$board_info->secret_yn ?></dd>
                            </dl>
                            <dl class="row">
                                <dt class="col-2">내용</dt>
                                <dd class="col-10"><?=htmlspecialchars_decode($board_info->contents) ?></dd>
                            </dl>
                            <dl class="row">
                                <dt class="col-2">등록일</dt>
                                <dd class="col-4"><?=$board_info->ins_date ?></dd>
                                <dt class="col-2">등록자</dt>
                                <dd class="col-4"><?=$board_info->ins_id ?></dd>
                            </dl>
                        </div><!-- /.card-body -->
                        <div class="card-footer">
                            <button type="button" class="btn btn-warning" id="board_cancel" name="board_cancel">뒤로가기</button>
                            <button type="button" class="btn btn-danger" id="board_delete" name="board_delete">삭제</button>
                            <button type="button" class="btn btn-info float-right" id="board_edit" name="board_edit">수정</button>
                        </div>
                    </div><!-- /.card -->
                </div><!-- ./col -->
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
        $("#board_cancel").click(function(e) {
            history.go(-1);
        });

        $("#board_delete").click(function(e) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-danger",
                    cancelButton: "btn btn-success"
                },
                buttonsStyling: false
            })

            swalWithBootstrapButtons.fire({
                title: "삭제하시겠습니까?",
                text: "삭제하면 되돌릴수 없습니다",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "삭제",
                cancelButtonText: "취소",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/board/delete",
                        type: "POST",
                        dataType: "json",
                        data: $("#frm").serialize(),
                        success: function(proc_result) {
                            var result = proc_result.result;
                            var message = proc_result.message;
                            if(result == true) {
                                swalWithBootstrapButtons.fire(
                                    "삭제",
                                    "삭제되었습니다",
                                    "error"
                                )
                                location.href = "/board/list";
                            } else {
                                alert(message);
                            }
                        }
                    });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                        "취소",
                        "취소되었습니다",
                        "success"
                    )
                }
            })
        });

        $("#board_edit").click(function(e) {
            location.href="/board/edit/<?=$board_info->board_idx ?>";
        });

    });

</script>

<?php   include_once APPPATH."Views/include/header.php"; // 헤더 ?>
<?php   include_once APPPATH."Views/include/navigation.php"; // 네비게이션(상단, 좌측 메뉴) ?>

<form id="frm" name="frm">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">휴폐업조회</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">홈</a></li>
                            <li class="breadcrumb-item active">휴폐업조회</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div><!-- /.content-header -->

        <section class="content"><!-- Main content -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">사업자등록번호 휴폐업조회</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="q">사업자번호</label>
                                    <input type="text" class="form-control" id="q" name="q" value="105-87-83592 ">
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="info-box bg-light">
                                            <div class="info-box-content">
                                                <span class="info-box-text text-center text-muted" id="business_no">조회 결과</span>
                                                <span class="info-box-number text-center text-muted mb-0" id="memo_text">여기에 결과가 표시됩니다</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <input type="button" id="q_search" name="q_search" class="btn btn-success float-right" value="조회">
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
</form>

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
        $.ajax({
            url: "/business/businessInfo",
            type: "POST",
            dataType: "json",
            async: true,
            data: $("#frm").serialize(),
            success: function(proc_result) {
                var result = proc_result.result;
                var message = proc_result.message;
                var business_no = proc_result.business_no+" 조회결과";
                var memo_text = proc_result.memo_text;
                if(result == true) {
                    $("#business_no").html(business_no);
                    $("#memo_text").html(memo_text);
                } else {
                    alert(message);
                }
            }
        });
    }
</script>
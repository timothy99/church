<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Registration Page</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"><!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="../assets/plugin/fontawesome-free/css/all.min.css?version=<?=CSS_VER ?>"><!-- Font Awesome -->
    <link rel="stylesheet" href="../assets/plugin/icheck-bootstrap/icheck-bootstrap.min.css?version=<?=CSS_VER ?>"><!-- icheck bootstrap -->
    <link rel="stylesheet" href="../assets/css/adminlte.min.css?version=<?=CSS_VER ?>"><!-- Theme style -->
</head>
<body class="hold-transition register-page">
<div class="register-box">
    <div class="register-logo">
        <a href="/"><b>관리자</b></a>
    </div>

    <div class="card">
        <div class="card-body register-card-body">
            <p class="login-box-msg">새로 가입합니다</p>

            <form id="frm" name="frm" method="post">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="user_name" name="user_name" placeholder="이름">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="email" class="form-control" id="user_id" name="user_id" placeholder="아이디(이메일)">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" id="user_password" name="user_password" placeholder="암호">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" id="user_password2" name="user_password2" placeholder="암호 확인">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="user_terms" name="user_terms" value="agree">
                            <label for="user_terms"><a href="#">약관</a>에 동의해요</label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="button" id="register" name="register" class="btn btn-primary btn-block">가입하기</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <a href="/user/login" class="text-center">이미 가입을 했어요</a>
        </div>
        <!-- /.form-box -->
    </div><!-- /.card -->
</div>
<!-- /.register-box -->

<script src="../assets/plugin/jquery/jquery.min.js?version=<?=JS_VER ?>"></script><!-- jQuery -->
<script src="../assets/plugin/bootstrap/js/bootstrap.bundle.min.js?version=<?=JS_VER ?>"></script><!-- Bootstrap 4 -->
<script src="../assets/js/adminlte.min.js?version=<?=JS_VER ?>"></script><!-- AdminLTE App -->
</body>
</html>

<script>

    $(function() {
        $("#register").click(function() {
            $.ajax({
                url: "/user/registerProc",
                type: "POST",
                dataType: "json",
                async: true,
                data: $("#frm").serialize(),
                success: function(proc_result) {
                    var result = proc_result.result;
                    var message = proc_result.message;
                    alert(message);
                    if(result == true) {
                        location.href = "/user/login";
                    }
                }
            });
        });
    });

</script>
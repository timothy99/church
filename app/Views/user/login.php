<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>로그인</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"><!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="../assets/plugin/fontawesome-free/css/all.min.css?version=<?=CSS_VER ?>"><!-- Font Awesome -->
    <link rel="stylesheet" href="../assets/plugin/icheck-bootstrap/icheck-bootstrap.min.css?version=<?=CSS_VER ?>"><!-- icheck bootstrap -->
    <link rel="stylesheet" href="../assets/css/adminlte.min.css?version=<?=CSS_VER ?>"><!-- Theme style -->
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="/"><b>관리자</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">로그인이 필요합니다</p>

            <form id="frm" name="frm" method="post">
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
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember">
                            <!-- <label for="remember">
                                기억하기
                            </label> -->
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="button" id="login" name="login" class="btn btn-primary btn-block">로그인</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <p class="mb-1">
                <a href="/user/forgot">로그인을 할 수 없습니다</a>
            </p>
            <p class="mb-0">
                <a href="/user/register" class="text-center">새로운 회원가입을 하고 싶습니다</a>
            </p>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->

<script src="../assets/plugin/jquery/jquery.min.js?version=<?=JS_VER ?>"></script><!-- jQuery -->
<script src="../assets/plugin/bootstrap/js/bootstrap.bundle.min.js?version=<?=JS_VER ?>"></script><!-- Bootstrap 4 -->
<script src="../assets/js/adminlte.min.js?version=<?=JS_VER ?>"></script><!-- AdminLTE App -->
</body>
</html>

<script>

    $(function() {
        $("#login").click(function() {
            $.ajax({
                url: "/user/loginProc",
                type: "POST",
                dataType: "json",
                async: true,
                data: $("#frm").serialize(),
                success: function(proc_result) {
                    var result = proc_result.result;
                    var message = proc_result.message;
                    if(result == true) {
                        location.href = "/dashboard/dashboard";
                    } else {
                        alert(message);
                    }
                }
            });
        });
    });

</script>
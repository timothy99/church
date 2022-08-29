<?php   include_once APPPATH."Views/include/header.php"; // 헤더 ?>
<?php   include_once APPPATH."Views/include/menu.php"; // 네비게이션(상단, 좌측 메뉴) ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">식단</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">홈</a></li>
                            <li class="breadcrumb-item">구내식당</li>
                            <li class="breadcrumb-item active">식단</li>
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
                            <div class="card-body">
                                <div id="calendar"></div>
                            </div>
                            <!-- /.card-body -->
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
        $("#upper-meal-calendar").addClass("menu-open");
        $("#a-meal-calendar").addClass("active");
        $("#bottom-meal-calendar").addClass("active");
    });

    // 달력생성
    $(function () {
        var date = new Date()
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear()

        var Calendar = FullCalendar.Calendar;
        var calendar_elemenet = document.getElementById("calendar");
        var calendar_render = new Calendar(calendar_elemenet, {
            dateClick: function(info) { // 날짜 클릭시 액션
                location.href = "/meal/view/"+info.dateStr;
            },
            eventClick: function(info) { // 이벤트 클릭시 액션
                location.href = "/meal/view/"+info.event.id;
            },
            headerToolbar: {
                left: "",
                center: "title",
                right: "today prev,next"
            },
            themeSystem: "bootstrap",
            locale: "ko",
            events: {
                url: "/meal/month",
                method: "POST"
            }
        });
        calendar_render.render();
    });

</script>
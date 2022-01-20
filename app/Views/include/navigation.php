<?php
    $current_uri = current_url(true);
    $segments = $current_uri->getSegments();
    $segment0 = isset($segments[0]) == false ? "dashboard" : $segments[0];
    $segment1 = isset($segments[1]) == false  ? "dashboard" : $segments[1];
?>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="../assets/plugin/adminlte3/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="/" class="brand-link">
            <img src="../assets/plugin/adminlte3/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">관리자</span>
        </a>

        <div class="sidebar"><!-- Sidebar -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex"><!-- Sidebar user panel (optional) -->
                <div class="image">
                    <img src="../assets/plugin/adminlte3/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="/user/logout" class="d-block">사용자 이름</a>
                </div>
            </div>

            <nav class="mt-2"><!-- Sidebar Menu -->
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                    <li class="nav-item <?php if (in_array($segment0, ["dashboard"])  && in_array($segment1, ["dashboard"])) echo "menu-open"; ?>">
                        <a href="/dashboard/dashboard" class="nav-link <?php if (in_array($segment0, ["dashboard"])  && in_array($segment1, ["dashboard"])) echo "active"; ?>">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>대시보드<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/dashboard/dashboard" class="nav-link <?php if (in_array($segment0, ["dashboard"])  && in_array($segment1, ["dashboard"])) echo "active"; ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>대시보드</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item <?php if (in_array($segment0, ["user"])  && in_array($segment1, ["userList"])) echo "menu-open"; ?>">
                        <a href="/user/userList" class="nav-link <?php if (in_array($segment0, ["user"])  && in_array($segment1, ["userList"])) echo "active"; ?>">
                            <i class="nav-icon fas fa-th"></i>
                            <p>회원관리<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/user/userList" class="nav-link <?php if (in_array($segment0, ["user"])  && in_array($segment1, ["userList"])) echo "active"; ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>회원목록</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav><!-- /.sidebar-menu -->
        </div><!-- /.sidebar -->
    </aside>
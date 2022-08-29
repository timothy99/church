<?php
    $user_info = $_SESSION["user_session"];
?>
    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="/image/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="index3.html" class="nav-link">Home</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="#" class="nav-link">Contact</a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="index3.html" class="brand-link">
            <img src="/image/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light"><?=SITE_NAME ?></span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="<?=$user_info->profile_image_base64 ?>" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="/myinfo/edit" class="d-block"><?=$user_info->user_name ?></a>
                </div>
                <div class="info">
                    <a href="/user/logout" class="d-block">로그아웃</a>
                </div>
            </div>

            <!-- SidebarSearch Form -->
            <div class="form-inline">
                <div class="input-group" data-widget="sidebar-search">
                    <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-sidebar">
                            <i class="fas fa-search fa-fw"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item" id="upper-dashboard-dashboard">
                        <a href="/dashboard/dashboard" class="nav-link" id="a-dashboard-dashboard">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>대시보드<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/dashboard/dashboard" class="nav-link" id="bottom-dashboard-dashboard">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>대시보드</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item" id="upper-member-list">
                        <a href="/member/list" class="nav-link" id="a-member-list">
                            <i class="nav-icon fas fa-th"></i>
                            <p>회원관리<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/member/list" class="nav-link" id="bottom-member-list">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>회원목록</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item" id="upper-business-search">
                        <a href="/business/search" class="nav-link" id="a-business-search">
                            <i class="nav-icon fas fa-book"></i>
                            <p>사업자정보<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/business/search" class="nav-link" id="bottom-business-search">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>휴폐업조회</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item" id="upper-meal-calendar">
                        <a href="/meal/list" class="nav-link" id="a-meal-calendar">
                            <i class="nav-icon fas fa-book"></i>
                            <p>구내식당<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/meal/calendar" class="nav-link" id="bottom-meal-calendar">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>식단(달력형)</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/meal/list" class="nav-link" id="bottom-meal-list">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>식단(목록형)</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
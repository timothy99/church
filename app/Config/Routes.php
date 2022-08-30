<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get("/", "Board::list"); // 메인
$routes->get("/batch/meal", "Batch::meal"); // 식단 배치
$routes->get("/batch/attendance", "Batch::attendance"); // 출석체크 이벤트 알림
$routes->get("/business/search", "Business::search"); // 휴폐업 조회 화면
$routes->get("/dashboard/dashboard", "Dashboard::dashboard"); // 메인
$routes->get("/meal/edit/(:any)", "Meal::edit/$1"); // 구내식당 데이터 수정
$routes->get("/meal/calendar", "Meal::calendar"); // 구내식당(달력형)
$routes->get("/meal/list", "Meal::list"); // 구내식당(목록형)
$routes->get("/meal/view/(:any)", "Meal::view/$1"); // 구내식당 데이터 보기
$routes->get("/member/edit/(:num)", "Member::edit/$1"); // 회원 정보
$routes->get("/member/list", "Member::list"); // 회원 목록
$routes->get("/member/view/(:num)", "Member::view/$1"); // 회원 정보
$routes->get("/user/login", "User::login"); // 로그인
$routes->get("/user/logout", "User::logout"); // 로그아웃
$routes->get("/user/register", "User::register"); // 가입시 등록폼
$routes->post("/business/result", "Business::result"); // 휴폐업 조회 로직
$routes->post("/meal/delete", "Meal::Delete"); // 식단 데이터 삭제
$routes->post("/meal/month", "Meal::month"); // 구내식당 월별 데이터
$routes->post("/meal/update", "Meal::update"); // 구내식당 데이터 저장
$routes->post("/member/update", "Member::update"); // 회원 정보 수정
$routes->post("/upload/profile", "Upload::profile"); // 프로필 이미지 업로드
$routes->post("/user/signin", "User::signin"); // 로그인 처리
$routes->post("/user/signup", "User::signup"); // 가입처리
$routes->get("/board/list", "Board::list"); // 게시판 목록 화면
$routes->get("/board/view/(:num)", "Board::view/$1"); // 게시판 보기 화면
$routes->get("/board/edit/(:num)", "Board::edit/$1"); // 게시판 수정 화면
$routes->post("/board/insert", "Board::insert"); // 게시판 입력/수정 로직
$routes->post("/board/update/(:num)", "Board::update/$1"); // 게시판 입력/수정 로직
$routes->post("/board/delete/(:num)", "Board::delete/$1"); // 게시판 삭제 로직

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

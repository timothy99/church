<?php

namespace Config;

use CodeIgniter\Events\Events;
use CodeIgniter\Exceptions\FrameworkException;
use App\Models\AuthorityModel; // 권한관리 모델

/*
 * --------------------------------------------------------------------
 * Application Events
 * --------------------------------------------------------------------
 * Events allow you to tap into the execution of the program without
 * modifying or extending core files. This file provides a central
 * location to define your events, though they can always be added
 * at run-time, also, if needed.
 *
 * You create code that can execute by subscribing to events with
 * the 'on()' method. This accepts any form of callable, including
 * Closures, that will be executed when the event is triggered.
 *
 * Example:
 *      Events::on('create', [$myInstance, 'myMethod']);
 */

Events::on('pre_system', static function () {
    if (ENVIRONMENT !== 'testing') {
        if (ini_get('zlib.output_compression')) {
            throw FrameworkException::forEnabledZlibOutputCompression();
        }

        while (ob_get_level() > 0) {
            ob_end_flush();
        }

        ob_start(static fn ($buffer) => $buffer);
    }

    /*
     * --------------------------------------------------------------------
     * Debug Toolbar Listeners.
     * --------------------------------------------------------------------
     * If you delete, they will no longer be collected.
     */
    if (CI_DEBUG && ! is_cli()) {
        Events::on('DBQuery', 'CodeIgniter\Debug\Toolbar\Collectors\Database::collect');
        Services::toolbar()->respond();
    }
});

/*
    사용자 추가 이벤트
    권한 모델을 만들어서 이벤트 체크
*/
Events::on("post_controller_constructor", function () {
    $authority_model = new AuthorityModel();

    $authority_model->checkBatch(); // 배치 작업에 접근 가능한 IP인지 확인
    // $authority_model->checkIp(); // 접근 가능한 IP인지 확인
    $authority_model->checkLogin(); // 로그인이 필요한 url인지 확인
});

// CI에서 기본적 DB이벤트(select등 모두 포함)가 발생되었을때의 로깅
Events::on("DBQuery", function () {
    logModifyQuery(); // insert, update, delete 만 로깅하도록 함수 생성
});

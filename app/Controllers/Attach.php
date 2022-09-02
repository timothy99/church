<?php namespace App\Controllers;

use App\Models\UploadModel;
use App\Models\FileModel;

// 파일첨부 관련 로직
class Attach extends BaseController
{
    // 프로필 이미지 업데이트
    public function profile()
    {
        $upload_model = new UploadModel();

        $user_file = $this->request->getFile("request_input"); // 올린 파일 정보 갖고 오기

        $is_valid = $user_file->isValid(); // 파일이 정상인지 확인
        if($is_valid == false) { // 올린 파일이 잘못된 경우
            throw new \RuntimeException($user_file->getErrorString()."(".$user_file->getError().")"); // 에러를 던진다
        } else { // 파일이 정상인 경우
            $proc_result = $upload_model->uploadProfile($user_file, 2, 160, 0); // 프로필 이미지 파일을 올린다.
        }

        echo json_encode($proc_result);
    }

    // 파일 보기 모드
    public function view()
    {
        $file_model = new FileModel();

        $file_id = $this->request->getUri()->getSegment(3);

        // 세션의 정보중 아이디를 갖고 옵니다.
        $session = \Config\Services::session();
        $user_session = $session->get("user_session");
        $user_id = $user_session->user_id;

        $file_info = $file_model->getFileInfo($user_id, $file_id); // 파일소유권 확인 및 파일 정보 확인
        $file_path = $file_model->getFilePath($file_info->file_directory, $file_info->file_name_uploaded); // 파일 업로드 경로 확보
        $raw_file = $file_model->getRawFile($this->response, $file_path); // 파일 다운로드

        return $raw_file;
    }

}

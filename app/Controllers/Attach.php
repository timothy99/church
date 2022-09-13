<?php namespace App\Controllers;

use App\Models\AttachModel;
use App\Models\FileModel;

// 파일첨부 관련 로직
class Attach extends BaseController
{
    // 프로필 이미지 업데이트
    public function profile()
    {
        $attach_model = new AttachModel();

        $user_file = $this->request->getFile("request_input"); // 올린 파일 정보 갖고 오기

        $is_valid = $user_file->isValid(); // 파일이 정상인지 확인
        if($is_valid == false) { // 올린 파일이 잘못된 경우
            throw new \RuntimeException($user_file->getErrorString()."(".$user_file->getError().")"); // 에러를 던진다
        } else { // 파일이 정상인 경우
            $proc_result = $attach_model->uploadProfile($user_file); // 프로필 이미지 파일을 올린다.
        }

        return json_encode($proc_result);
    }

    // 파일 보기 모드
    public function view()
    {
        $file_model = new FileModel();

        $file_id = $this->request->getUri()->getSegment(3);
        $user_id = getUserSessionInfo("user_id");

        $file_info = $file_model->getFileInfo($user_id, $file_id); // 파일소유권 확인 및 파일 정보 확인
        $file_path = $file_model->getFilePath($file_info->file_directory, $file_info->file_name_uploaded); // 파일 업로드 경로 확보
        $raw_file = $file_model->getRawFile($this->response, $file_path); // 파일 다운로드

        return $raw_file;
    }

    // 파일 다운로드 모드
    public function download()
    {
        $file_model = new FileModel();

        $file_id = $this->request->getUri()->getSegment(3);
        $user_id = getUserSessionInfo("user_id");

        $file_info = $file_model->getFileInfo($user_id, $file_id); // 파일소유권 확인 및 파일 정보 확인
        $file_path = $file_model->getFilePath($file_info->file_directory, $file_info->file_name_uploaded); // 파일 업로드 경로 확보
        $file_download = $this->response->download($file_path, null)->setFileName($file_info->file_name_org); // 파일 다운로드

        return $file_download;
    }

    // 게시판 파일이나 이미지 업로드
    public function board()
    {
        $attach_model = new AttachModel();

        $result = true;
        $message = "파일 업로드를 시작합니다.";
        $file_id = 0;

        $proc_result = array();
        $proc_result["result"] = $result;
        $proc_result["message"] = $message;
        $proc_result["file_id"] = $file_id;
        $proc_result["file_path"] = "/attach/view/".$file_id;

        $user_file = $this->request->getFile("file"); // 올린 파일 정보 갖고 오기

        $is_valid = $user_file->isValid(); // 파일이 정상인지 확인
        if($is_valid == false) { // 올린 파일이 잘못된 경우
            throw new \RuntimeException($user_file->getErrorString()."(".$user_file->getError().")"); // 에러를 던진다
        } else { // 파일이 정상인 경우
            $validation_rule = ["file"=>["label"=>"Image File", "rules"=>"uploaded[file]|is_image[file]"]]; // 이미지인지 검증
            $validation_result = $this->validate($validation_rule);
            if ($validation_result == false) { // 이미지가 아닌 경우
                $proc_result = $attach_model->uploadBoardFile($user_file); // 파일을 올린다.
            } else { // 이미지 파일인 경우
                $proc_result = $attach_model->uploadBoardImage($user_file); // 파일을 올린다.
            }
        }

        return json_encode($proc_result);
    }

}

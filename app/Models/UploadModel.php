<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\FileModel;
use Throwable;

class UploadModel extends Model
{
    /**
     * [Description for uploadProfile]
     * 프로필 이미지 업로드
     * width, height 모두 0을 입력하면 리사이징을 실행하지 않는다.
     *
     * @param object $user_file - 파일 객체
     * 
     * @return array
     * 
     * @author     timothy99
     */
    public function uploadProfile(object $user_file) : array
    {
        $file_model = new FileModel();

        $limit_size = 2; // 2메가 바이트 업로드 제한 사이즈 메가바이트 단위로 입력
        $width = 160; // 가로 해상도 160, 가로 해상도로 0을 입력하면 세로를 기준으로 리사이징을 한다
        $height = 0; // 세로 해상도에 따라 조정, 세로 해상도로 0을 입력하면 가로를 기준으로 리사이징을 한다

        $proc_result = array();

        $result = true;
        $message = "파일업로드 시작";
        $file_id = 0;

        // mimetype이 정상인지 확인한다
        $mime_type = $user_file->getMimeType();
        $check_mime_type = $file_model->checkMimeType($mime_type, "image"); // 이미지 파일용 체크
        if($check_mime_type == false) {
            $result = false;
            $message = "이미지가 아닙니다.";
        }

        // 허용된 이미지 크기를 넘지 않는지 확인한다.
        $image_size = $user_file->getSize();
        $check_image_size = $file_model->checkImageSize($image_size, $limit_size);
        if($check_image_size == false) {
            $result = false;
            $message = "이미지가 큽니다";
        }

        if($result == true) {
            // 이미지를 저장하고 저장된 경로를 반환한다.
            $file_info = $file_model->saveFile($user_file);
            $file_path = $file_info["file_directory"]."/".$file_info["file_name_uploaded"];
            $file_size = $file_model->resizeImageFile($file_path, $width, $height); // 이미지 리사이즈 하기

            // 위에서 구한 파일의 크기와 형식을 저장
            $file_info["file_size"] = $file_size;
            $file_info["mime_type"] = $mime_type;
            $file_info["category"] = "image";
            $model_result = $file_model->insertFileInfo($file_info); // DB에 파일 정보 저장
            $result = $model_result["result"];
            $message = $model_result["message"];
            $file_id = $model_result["file_id"];
        }

        $proc_result["result"] = $result;
        $proc_result["message"] = $message;
        $proc_result["file_id"] = $file_id;

        return $proc_result;
    }

}

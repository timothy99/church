<?php namespace App\Controllers;

use App\Models\FileModel;
use App\Models\MemberModel;

class Upload extends BaseController
{
    // 프로필 이미지 업데이트
    public function profile()
    {
        $file_model = new FileModel();
        $member_model = new MemberModel();

        $result = true;
        $message = "이미지가 맞습니다.";
        $image_base64 = null;

        // 파일 정보 갖고 오기
        $user_file = $this->request->getFile("request_input");

        // 파일이 정상인지 확인
        $is_valid = $user_file->isValid();
        if($is_valid == false) { // 올린 파일이 잘못된 경우
            throw new \RuntimeException($user_file->getErrorString()."(".$user_file->getError().")"); // 에러를 던진다
        } else { // 파일이 정상인 경우
            // mimetype이 정상인지 확인한다
            $mime_type = $user_file->getMimeType();
            $check_mime_type = $file_model->checkMimeType($mime_type, "image"); // 이미지 파일용 체크
            if($check_mime_type == false) {
                $result = false;
                $message = "이미지가 아닙니다.";
            }

            // 허용된 이미지 크기를 넘지 않는지 확인한다.
            $image_size = $user_file->getSize();
            $limit_size = 10; // 메가바이트 단위로 입력한다.
            $check_image_size = $file_model->checkImageSize($image_size, $limit_size);
            if($check_image_size == false) {
                $result = false;
                $message = "이미지가 큽니다";
            }

            if($result == true) {
                // 이미지를 저장하고 저장된 경로를 반환한다.
                $file_info = $file_model->saveFile($user_file);
                $file_name_uploaded = $file_info["file_name_uploaded"];

                // 위에서 구한 파일의 크기와 형식을 저장
                $file_info["file_size"] = $image_size;
                $file_info["mime_type"] = $mime_type;
                $file_info["category"] = "image";

                $model_result = $file_model->insertFileInfo($file_info); // DB에 파일 정보 저장
                $insert_id = $model_result["insert_id"];
                $model_result = $file_model->resizeImageFile($file_name_uploaded, 160, 160); // 이미지 리사이즈 하기
                $image_base64 = $file_model->getImageFileInfo($insert_id); // DB에 이미지 파일 정보 갖고오기
            }
        }

        $proc_result = array();
        $proc_result["result"] = $result;
        $proc_result["message"] = $message;
        $proc_result["profile_image"] = $insert_id;
        $proc_result["image_base64_html"] = "<img src=\"".$image_base64."\">";

        echo json_encode($proc_result);
    }

}


<?php

namespace App\Models;

use CodeIgniter\Model;
use Throwable;

class FileModel extends Model
{
    // 확장자 체크 해서 필터에 따라 분류가 맞는지 확인
    public function checkMimeType($user_file_type, $check_type)
    {
        $mime_type = array();
        // 이미지용 mime_type 처리
        if ($check_type == "image") {
            $mime_type[] = "image/png";
            $mime_type[] = "image/jpg";
            $mime_type[] = "image/gif";
        } else {
            // 기타 사항에 대해서 처리는 아직 정해지지 않음
        }
        $check_mime_type = in_array($user_file_type, $mime_type);

        return $check_mime_type;
    }

    // 이미지 사이즈 체크해서 우리가 설정한 크기와 맞는지 확인
    public function checkImageSize($image_size, $limit_size)
    {
        $limit_size = $limit_size*1024*1024; // MB단위로 입력된 숫자를 바이트 단위로 변경
        // 입력받은 이미지 사이즈와 비교해서
        if ($image_size > $limit_size) { // 이미지 사이즈가 크면
            $check_image_size = false; // false 반환
        } else { // 이미지 사이즈가 규정보다 작으면
            $check_image_size = true; // true 반환
        }

        return $check_image_size;
    }

    // 파일을 저장한다.
    public function saveFile($user_file)
    {
        $upload_date_path = date("Y/m"); // 업로드 디렉토리는 연/월로 생성
        $random_name = $user_file->getRandomName(); // 랜덤네임 생성
        $uploaded_file_name = $user_file->store($upload_date_path, $random_name); // 저장

        $file_info = array();
        $file_info["file_name_org"] = $user_file->getClientName();
        $file_info["file_name_stored"] = $random_name;
        $file_info["file_name_uploaded"] = $uploaded_file_name;

        return $file_info;
    }

    // 파일 정보 DB에 저장
    public function insertFileInfo($file_info)
    {
        $file_name_org = $file_info["file_name_org"];
        $file_name_stored = $file_info["file_name_stored"];
        $file_name_uploaded = $file_info["file_name_uploaded"];
        $file_size = $file_info["file_size"];
        $mime_type = $file_info["mime_type"];
        $category = $file_info["category"];

        // 세션의 정보중 아이디를 갖고 옵니다.
        $session = \Config\Services::session();
        $user_session = $session->get("user_session");
        $user_id = $user_session->user_id;

        $today = date("YmdHis");

        $result = true;
        $message = "입력이 잘 되었습니다";

        try {
            $db = db_connect();
            $db->transStart();
            $builder = $db->table("gwt_file");
            $builder->set("file_name_org", $file_name_org);
            $builder->set("file_name_stored", $file_name_stored);
            $builder->set("file_name_uploaded", $file_name_uploaded);
            $builder->set("file_size", $file_size);
            $builder->set("mime_type", $mime_type);
            $builder->set("category", $category);
            $builder->set("del_yn", "N");
            $builder->set("ins_id", $user_id);
            $builder->set("ins_date", $today);
            $builder->set("upd_id", $user_id);
            $builder->set("upd_date", $today);
            $result = $builder->insert();
            $insert_id = $db->insertID();
            $db->transComplete();
        } catch (Throwable $t) {
            $result = false;
            $message = "입력에 오류가 발생했습니다.";
            logMessage($t->getMessage());
        }

        $model_result = array();
        $model_result["result"] = $result;
        $model_result["message"] = $message;
        $model_result["insert_id"] = $insert_id;

        return $model_result;
    }

    // 업로드된 이미지 파일의 정보를 갖고 온다.
    public function getImageFileInfo($file_idx)
    {
        $db = db_connect();
        $builder = $db->table("gwt_file");
        $builder->select("file_name_uploaded");
        $builder->select("mime_type");
        $builder->where("file_idx", $file_idx);
        $builder->where("category", "image");
        $builder->where("del_yn", "N");
        $db_info = $builder->get()->getFirstRow(); // 쿼리 실행

        $file_name_uploaded = $db_info->file_name_uploaded;
        $mime_type = $db_info->mime_type;

        $image_file = new \CodeIgniter\Files\File(WRITEPATH."uploads/".$file_name_uploaded, true);

        $file_path = $image_file->getPathname();

        $data = file_get_contents($file_path);
        $image_base64 = "data:".$mime_type.";base64,".base64_encode($data);

        return $image_base64;
    }

    // 이미지 파일 리사이즈
    public function resizeImageFile($file_path, $width, $height)
    {
        $image_path = UPLOADPATH.$file_path;
        $mster_dimension = "auto";
        $image = \Config\Services::image();
        $image->withFile($image_path); // 어느 이미지 수정할지 결정

        // 이미지 크기를 0으로 지정할 경우 비율에 맞추는 이미지로 설정
        if ($width == 0) {
            $mster_dimension = "height";
        } elseif ($height == 0) {
            $mster_dimension = "width";
        }

        $image->resize($width, $height, true, $mster_dimension);
        $image->save($image_path);
    }

}

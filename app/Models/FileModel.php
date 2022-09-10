<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\SecurityModel;
use Throwable;
use stdClass;

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
            $mime_type[] = "image/jpeg";
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
        $security_model = new SecurityModel();

        $upload_date_path = date("Y/m"); // 업로드 디렉토리는 연/월로 생성
        $random_name = $user_file->getRandomName(); // 랜덤네임 생성
        $user_file->store($upload_date_path, $random_name); // 저장
        $file_id = $security_model->getRandomString(4, 32); // 보안을 위한 랜덤문자 생성

        $file_info = array();
        $file_info["file_name_org"] = $user_file->getClientName();
        $file_info["file_directory"] = $upload_date_path;
        $file_info["file_name_uploaded"] = $random_name;
        $file_info["file_id"] = $file_id;

        return $file_info;
    }

    // 파일 정보 DB에 저장
    public function insertFileInfo($file_info)
    {
        $file_name_org = $file_info["file_name_org"];
        $file_directory = $file_info["file_directory"];
        $file_name_uploaded = $file_info["file_name_uploaded"];
        $file_size = $file_info["file_size"];
        $mime_type = $file_info["mime_type"];
        $category = $file_info["category"];
        $file_id = $file_info["file_id"];
        $public_yn = $file_info["public_yn"] ?? "N";

        $user_id = getUserSessionInfo("user_id"); // 세션의 정보중 아이디를 갖고 옵니다.

        $today = date("YmdHis");

        $result = true;
        $message = "입력이 잘 되었습니다";

        try {
            $db = db_connect();
            $db->transStart();
            $builder = $db->table("gwt_file");
            $builder->set("file_id", $file_id);
            $builder->set("file_name_org", $file_name_org);
            $builder->set("file_directory", $file_directory);
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
        $model_result["file_id"] = $file_id;

        return $model_result;
    }

    // 이미지 파일 리사이즈
    public function resizeImageFile($file_path, $width, $height)
    {
        $image_path = UPLOADPATH.$file_path;

        if($width == 0 && $height == 0) {
            // 이미지 처리를 하지 않는다
        } else {
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

        // 파일에 대한 저장 용량 얻기
        $file = new \CodeIgniter\Files\File($image_path);
        $file_size = $file->getSize();

        return $file_size;
    }

    public function getFileInfo($user_id, $file_id)
    {
        $db = db_connect();
        $builder = $db->table("gwt_file");
        $builder->select("file_id");
        $builder->select("file_directory");
        $builder->select("file_name_uploaded");
        $builder->select("file_size");
        $builder->select("mime_type");
        $builder->select("category");
        $builder->select("public_yn");
        $builder->select("ins_id");
        $builder->where("file_id", $file_id);
        $builder->where("del_yn", "N");
        $db_info = $builder->get()->getFirstRow(); // 쿼리 실행

        $public_yn = $db_info->public_yn;
        if($public_yn == "Y") { // 공개된 파일이면
            // 아무일도 안함
        } elseif ($public_yn == "N") { // 공개 안되는 파일이지만
            $db_user_id = $db_info->ins_id;
            if ($db_user_id == $user_id) { // 내 파일이라면
                // 아무일도 안함
            } else { // 공개되면 안되는 파일이면서 내 파일도 아니면 빈값
                $db_info->file_id = null;
                $db_info->file_directory = null;
                $db_info->file_name_uploaded = null;
                $db_info->file_size = null;
                $db_info->mime_type = null;
                $db_info->category = null;
                $db_info->public_yn = null;
                $db_info->ins_id = null;
            }
        }

        return $db_info;
    }

    // 서버에 저장된 파일의 경로 생성
    public function getFilePath($file_directory, $file_name_uploaded)
    {
        $file_path = UPLOADPATH.$file_directory."/".$file_name_uploaded;

        return $file_path;
    }

    public function getRawFile($response, $file_path)
    {
        $raw_file = $response->download($file_path, null); // 파일 다운로드

        return $raw_file;
    }

}


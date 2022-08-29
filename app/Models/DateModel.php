<?php namespace App\Models;

use CodeIgniter\Model;

class DateModel extends Model
{
    // 14자리 텍스트를 날짜(연-월-일 시:분:초)로 만들기
    public function convertTextToDate($date_text, $input_type, $output_type)
    {
        /**
         * 1 : YmdHis
         */
        if($input_type == "1") {
            $input_date = date_create_from_format("YmdHis", $date_text);
        }

        /**
         * 1 : Y-m-d H:i:s
         * 2 : Y-m-d
         */
        if($output_type == "1") {
            $output_date = $input_date->format("Y-m-d H:i:s");
        } else if($output_type == "2") {
            $output_date = $input_date->format("Y-m-d");
        }

        return $output_date;
    }

}
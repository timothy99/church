<?php namespace App\Models;

use CodeIgniter\Model;

class TextModel extends Model
{
    /**
     * @author 배진모
     * @see 콤마가 들어간 소수점 문자 데이터를 소수점으로 변환
     * @param string $input_string - 콤마가 들어간 텍스트
     * @return string $return_string - 콤마가 제외되고 float형식으로 변경된 숫자
     */
    public function getTextToFloat($input_string)
    {
        $return_string = str_replace(",","",$input_string);
        $return_number = floatval($return_string);

        return $return_number;
    }

    /**
     * @author 배진모
     * @see 콤마가 들어간 소수점 문자 데이터를 소수점으로 변환
     * @param string $input_string - 콤마가 들어간 텍스트
     * @return string $return_number - 콤마가 제외되고 int형으로 변경된 숫자
     */
    public function getTextToInteger($input_string)
    {
        $return_string = str_replace(",","",$input_string);
        $return_number = (int)($return_string);

        return $return_number;
    }

    /**
     * @author 배진모
     * @see 10자리 또는 11자리로 된 휴대전화 번호 나누기
     * @param string $input_string - 휴대전화 번호
     * @return string $return_number - 콤마가 제외되고 int형으로 변경된 숫자
     */
    public function getMobileConvert($input_string)
    {
        $mobile_length = strlen($input_string);
        if($mobile_length == 10) {
            $mobile1 = substr($input_string, 0, 3);
            $mobile2 = substr($input_string, 3, 3);
            $mobile3 = substr($input_string, 6, 4);
        } else if($mobile_length == 11) {
            $mobile1 = substr($input_string, 0, 3);
            $mobile2 = substr($input_string, 3, 4);
            $mobile3 = substr($input_string, 7, 4);
        } else {
            $mobile1 = "000";
            $mobile2 = "0000";
            $mobile3 = "0000";
        }

        $mobile_txt = "$mobile1-$mobile2-$mobile3";

        return $mobile_txt;
    }

    /**
     * @author 배진모
     * @see 대시 있는 휴대전화 번호의 데이터 정리
     * @param string $input_string - 휴대전화 번호
     * @return string $return_number - 콤마가 제외되고 int형으로 변경된 숫자
     */
    public function getReplaceDash($input_string)
    {
        $result_txt = trim($input_string);
        $result_txt = str_replace("-", "", $result_txt);

        return $result_txt;
    }

    /**
     * @author 배진모
     * @see 16자리 카드 번호 나누기
     * @param string $input_string - 휴대전화 번호
     * @return string $return_number - 콤마가 제외되고 int형으로 변경된 숫자
     */
    public function getCardNo($input_string)
    {
        $card1 = substr($input_string, 0, 4);
        $card2 = substr($input_string, 4, 4);
        $card3 = substr($input_string, 8, 4);
        $card4 = substr($input_string, 12, 4);

        $card_txt = "$card1-$card2-$card3-$card4";

        return $card_txt;
    }

    /**
     * @author 배진모
     * @see 16자리 카드 번호 대시 지우기
     * @param string $input_string - 휴대전화 번호
     * @return string $return_number - 콤마가 제외되고 int형으로 변경된 숫자
     */
    public function getCardNo2($input_string)
    {
        $card_txt = str_replace("-", "", $input_string);

        return $card_txt;
    }

    /**
     * @author 배진모
     * @see 대시가 있는 사업자 번호를 10자리 숫자만 남기기
     * @param string $input_string - 사업자 번호
     * @return string $return_number - 10자리 숫자
     */
    public function getBusinessNumber($input_string)
    {
        $business_number = trim($input_string);
        $business_number = str_replace("-", "", $business_number);

        return $business_number;
    }

    /**
     * @author 배진모
     * @see 사업자 번호 10자리를 대시 있게 만들기
     * @param string $input_string - 10자리 사업자 번호 숫자만
     * @return string $return_number - 대시 포함
     */
    public function getBusinessNumber2($input_string)
    {
        $business_number = trim($input_string);

        $business_number1 = substr($input_string, 0, 3);
        $business_number2 = substr($input_string, 3, 2);
        $business_number3 = substr($input_string, 5, 5);

        $business_number = $business_number1."-".$business_number2."-".$business_number3;

        return $business_number;
    }

}
<?php namespace App\Models;

use CodeIgniter\Model;
use App\Models\TextModel;

/**
 * [Description BusinessModel]
 * 사업자 관련
 */
class BusinessModel extends Model
{
    /**
     * [Description for getBusinessApiInfo]
     * 홈택스의 휴폐업 조회 정보 갖고 오기 API
     *
     * @param   string  $business_number    사업자번호
     * 
     * @return  array
     * 
     * @author  timothy99
     */
    public function getBusinessInfo(string $business_number) : array
    {
        $result = true;
        $message = "조회가 완료되었습니다";

        $text_model = new TextModel();
        $business_number = $text_model->getBusinessNumber($business_number);

        // 국세청 API를 통해 데이터 조회
        $nts_api_key_encoding = env("nts.api.key.encoding");
        $url = "https://api.odcloud.kr/api/nts-businessman/v1/status?serviceKey=".$nts_api_key_encoding;

        $headers = array();
        $headers[] = "Content-type:application/json;";
        $headers[] = "Accept: Type:application/json";
        $headers[] = "Cache-Control: no-cache";
        $headers[] = "Pragma: no-cache";
        $headers[] = "SOAPAction: \"run\"";

        $request["b_no"] = [$business_number];
        $request_json = json_encode($request);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS,  $request_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $data = curl_exec($ch);
        curl_close($ch);

        $api_info = json_decode($data);
        $api_status_code = $api_info->status_code;

        // 요청은 항상 1건만, 결과도 항상 1건이므로 제일 첫줄 배열만 반환환다.
        if($api_status_code == "OK") {
            $business_api_info = $api_info->data[0];
            $business_number = $business_api_info->b_no; // 사업자등록번호
            $status_name = $business_api_info->b_stt; // 납세자상태(명칭)
            $status_code = $business_api_info->b_stt_cd; // 납세자상태(코드)
            $tax_type_name = $business_api_info->tax_type; // 과세유형메세지(명칭)
            $tax_type_code = $business_api_info->tax_type_cd; // 과세유형메세지(코드)
            $end_date = $business_api_info->end_dt; // 폐업일 (YYYYMMDD 포맷)
            $utcc_yn = $business_api_info->utcc_yn; // 단위과세전환폐업여부(Y,N)
            $tax_type_change_date = $business_api_info->tax_type_change_dt; // 최근과세유형전환일자 (YYYYMMDD 포맷)
            $invoice_apply_date = $business_api_info->invoice_apply_dt; // 세금계산서적용일자 (YYYYMMDD 포맷)

            $business_info = (object)null;
            $business_info->business_number = substr($business_number,0,3)."-".substr($business_number,3,2)."-".substr($business_number,5,5);
            $business_info->status_name = $status_name;
            $business_info->status_code = $status_code;
            $business_info->tax_type_name = $tax_type_name;
            $business_info->tax_type_code = $tax_type_code;
            $business_info->end_date = $end_date;
            $business_info->utcc_yn = $utcc_yn;
            $business_info->tax_type_change_date = $tax_type_change_date;
            $business_info->invoice_apply_date = $invoice_apply_date;
        } else {
            $result = false;
            $message = "API문제로 조회가 되지 않았습니다.";
        }

        $business_api_info = array();
        $business_api_info["result"] = $result;
        $business_api_info["message"] = $message;
        $business_api_info["business_info"] = $business_info;

        return $business_api_info;
    }

}
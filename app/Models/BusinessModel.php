<?php namespace App\Models;

use CodeIgniter\Model;

class BusinessModel extends Model
{
    /**
     * @author 배진모
     * @see 홈택스의 휴폐업 조회 정보 갖고 오기
     * @param char license_num - 사업자번호
     * @return json 홈택스 조회 결과
     */
    public function getBusinessInfo($data)
    {
        $result = true;
        $message = "조회가 완료되었습니다";

        $query_model = new QueryModel();
        $text_model = new TextModel();

        $license_num = $data["license_num"];

        $business_number = $text_model->getBusinessNumber($license_num);

        // XML로 홈택스에서 조회
        $url = "https://teht.hometax.go.kr/wqAction.do?actionId=ATTABZAA001R08&screenId=UTEABAAA13&popupYn=false&realScreenId=";
        $xmlRequest = "
                        <map id='ATTABZAA001Ra08'>
                            <pubcUserNo/>
                            <mobYn>N</mobYn>
                            <inqrTrgtClCd>1</inqrTrgtClCd>
                            <txprDscmNo>$business_number</txprDscmNo>
                            <dongCode>__MIDDLE__</dongCode>
                            <psbSearch>Y</psbSearch>
                            <map id='userReqInfoVO'/>
                        </map>
        ";
        $headers = array(
                            "Content-type: text/xml;charset=\"utf-8\"",
                            "Accept: text/xml",
                            "Cache-Control: no-cache",
                            "Pragma: no-cache",
                            "SOAPAction: \"run\""
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS,  $xmlRequest);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $data = curl_exec($ch);

        $memo_text = null;
        $business_no = null;

        $pos = strpos($data, "과부하"); // 홈택스 접속을 제한한다는 문구가 있음을 '과부하'라는 글자로 판단
        if($pos > 0) {
            $result = false;
            $message = $data;
        } else if($data != null) {
            // XML파싱
            $xml_arr = simplexml_load_string($data);
            $result_text = $xml_arr->trtCntn[0];
            $business_no = substr($business_number,0,3)."-".substr($business_number,3,2)."-".substr($business_number,5,5); // 사업자번호 분리
            $today = date("y-m-d");
            $today_text = "[".$today."]";
            $memo_text = $today_text.$result_text;

            if($result_text == null) {
                $result = false;
                $message = "오류가 발생하였습니다. 홈택스의 접속제한이 걸린상황일수도 있습니다.";
            }
        }

        $proc_result = array();
        $proc_result["result"] = $result;
        $proc_result["message"] = $message;
        $proc_result["memo_text"] = $memo_text;
        $proc_result["business_no"] = $business_no;

        sleep(1); // 지속적인 데이터 조회하면 잠깐 중지되므로 1초씩 쉰다.

        return $proc_result;
    }

}
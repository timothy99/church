<?php namespace App\Controllers;

use App\Models\BusinessModel;

class Business extends BaseController
{
    /**
     * @author 배진모
     * @see 휴폐업 조회 화면
     * @param null
     * @return view
     */
    public function businessSearch()
    {
        $view = view("business/businessSearch");

        return $view;
    }

    /**
     * @author 배진모
     * @see 휴폐업 조회
     * @param post
     * @return json
     */
    public function businessInfo()
    {
        $business_model = new BusinessModel();

        $license_num = $this->request->getPost("q") == null ? null : $this->request->getPost("q");

        $business_info = $business_model->getBusinessInfo($license_num); // 휴폐업조회

        echo json_encode($business_info);
    }

}
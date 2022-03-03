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
    public function businessSearch() : string
    {
        $view = view("business/businessSearch");

        return $view;
    }

    /**
     * [Description for businessInfo]
     * 휴폐업 조회
     *
     * @return  string
     * 
     * @author  timothy99
     */
    public function businessInfo() : void
    {
        $business_model = new BusinessModel();

        $license_num = $this->request->getPost("q") == null ? null : $this->request->getPost("q");

        $business_info = $business_model->getBusinessInfo($license_num); // 휴폐업조회

        echo json_encode($business_info);
    }

    /**
     * @author 배진모
     * @see 휴폐업 조회 화면 API로 조회하는 화면
     * @param null
     * @return view
     */
    public function businessApiSearch()
    {
        $view = view("business/businessApiSearch");

        return $view;
    }

    /**
     * @author 배진모
     * @see 휴폐업 조회 API
     * @param post
     * @return json
     */
    public function businessApiInfo()
    {
        $business_model = new BusinessModel();

        $business_number = $this->request->getPost("q") == null ? null : $this->request->getPost("q");

        $business_info = $business_model->getBusinessApiInfo($business_number); // 휴폐업조회

        echo json_encode($business_info);
    }

}
<?php namespace App\Controllers;

use App\Models\BusinessModel;

class Business extends BaseController
{
    /**
     * [Description for businessApiSearch]
     * 휴폐업 조회 화면 API로 조회하는 화면
     *
     * @return string
     * 
     * @author     timothy99
     */
    public function search() : string
    {
        $view = view("business/search");

        return $view;
    }

    /**
     * [Description for businessApiInfo]
     * 휴폐업 조회 API
     *
     * @return view
     * 
     * @author     timothy99
     */
    public function result() : void
    {
        $business_model = new BusinessModel();

        $business_number = $this->request->getPost("q") == null ? null : $this->request->getPost("q");

        $business_info = $business_model->getBusinessInfo($business_number); // 휴폐업조회

        echo json_encode($business_info);
    }

}
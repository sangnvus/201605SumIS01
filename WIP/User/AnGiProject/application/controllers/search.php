<?php

defined('BASEPATH') OR exit('No direct script access allow');

class Search extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model(array('Search_model', 'Restaurants_model', 'Image_model'));
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
    }

    public function index() {
        $this->searchRestaurant();
    }

    public function searchRestaurant() {
        $term = $this->input->get('userTerm');
        $cateResult = $this->Search_model->searchCategory($term);
        $restResult = $this->Search_model->searchRestFood($term);

        // helper model
        $restProfile = $this->Restaurants_model->getInterestingRestaurants();
        $isCate = false;
        $isRest = false;

        // if restaurant category found
        if (count($cateResult) > count($restProfile)) {
            $loopControl = $cateResult;
            $innerLoop = $restProfile;
        } else {
            $loopControl = $restProfile;
            $innerLoop = $cateResult;
        }
        $cateList = array();
        foreach ($loopControl as $row) {
            for ($i = 0; $i < count($innerLoop); $i++) {
                if ($row->restaurantID == $innerLoop[$i]->restaurantID) {
                    if (count($cateResult) > count($restProfile)) {

                        $cateName = $row->nameCOR;

                        $campaign = $innerLoop[$i]->campaign;
                        $address = $innerLoop[$i]->address;
                        $discount = $innerLoop[$i]->discount;
                        $restName = $innerLoop[$i]->nameRe;
                        $imageAddr = $innerLoop[$i]->addressImage;
                        $average = $innerLoop[$i]->average;
                    } else {

                        $cateName = $innerLoop[$i]->nameCOR;

                        $campaign = $row->campaign;
                        $address = $row->address;
                        $discount = $row->discount;
                        $restName = $row->nameRe;
                        $imageAddr = $row->addressImage;
                        $average = $row->average;
                    }
                    $rprofile = array(
                        'restID' => $row->restaurantID,
                        'campaign' => $campaign,
                        'address' => $address,
                        'discount' => $discount,
                        'restName' => $restName,
                        'category' => $cateName,
                        'imageAddr' => $imageAddr,
                        'average' => $average
                    );
                    array_push($cateList, $rprofile);
                }
            }
        }

        // if restaurant name or food found
        if (count($restResult) > count($restProfile)) {
            $loopControl1 = $restResult;
            $innerLoop1 = $restProfile;
        } else {
            $loopControl1 = $restProfile;
            $innerLoop1 = $restResult;
        }
        // if restaurant category found
        $restList = array();
        foreach ($loopControl1 as $row) {
            for ($i = 0; $i < count($innerLoop1); $i++) {
                if ($row->restaurantID == $innerLoop1[$i]->restaurantID) {
                    $rprofile = array(
                        'restID' => $row->restaurantID,
                        'campaign' => $row->campaign,
                        'address' => $row->address,
                        'discount' => $row->discount,
                        'restName' => $row->nameRe,
                        'imageAddr' => $row->addressImage,
                        'average' => $row->average
                    );
                    array_push($restList, $rprofile);
                }
            }
        }
        
        // check if search keyword found
        if (count($cateList)) {
            $isCate = true;
        }
        if (count($restList)) {
            $isRest = true;
        }
        
        $data = array();
        $data['totalResult'] = count($cateList) + count($restList);;
        $data['keyword'] = $term;
        $data['isCate'] = $isCate;
        $data['isRest'] = $isRest;
        $data['cateList'] = $cateList;
        $data['restList'] = $restList;
        $data['content'] = 'site/home/search/index.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }

}

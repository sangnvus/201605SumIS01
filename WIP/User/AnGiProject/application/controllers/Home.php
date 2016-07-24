<?php

defined('BASEPATH') OR exit('No direct script access allow');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('url', 'form'));
        $this->load->model(array('Restaurants_model', 'Category_model'));
        $this->load->library('session');
    }

    public function index() {
        $topRating = array();
        $highestDiscount = array();
        // retrieve data from model
        $restProfile = $this->Restaurants_model->getInterestingRestaurants();
        foreach ($restProfile as $row) {
            $rprofile = array(
                'restID' => $row->restaurantID,
                'campaign' => $row->campaign,
                'address' => $row->address,
                'discount' => $row->discount,
                'restName' => $row->nameRe,
                'imageAddr' => $row->addressImage,
                'average' => $row->average
            );
            array_push($topRating, $rprofile);
            array_push($highestDiscount, $rprofile);
        }
        
        $topRating = $this->sortRestProfile($topRating, 'average');
        $highestDiscount = $this->sortRestProfile($highestDiscount, 'discount');
        
        $categoriesData = $this->Category_model->getCategories();
        $data['categoriesData'] = $categoriesData;

        $data['topRating'] = $topRating;
        $data['highestDiscount'] = $highestDiscount;

        $data['limitRate'] = 4;
        $data['limitDis'] = 8;

        // return to view
        $data['dbMsg'] = "<div class='alert alert-danger text-center'>chưa có thông tin nhà hàng!!!</div>";

        $data['content'] = 'site/home/index/index.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }

    // sorting function
    function sortRestProfile($a, $subkey) {
        foreach ($a as $k => $v) {
            $b[$k] = strtolower($v[$subkey]);
        }
        arsort($b);
        foreach ($b as $key => $val) {
            $c[] = $a[$key];
        }
        return $c;
    }

}

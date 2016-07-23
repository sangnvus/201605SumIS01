<?php

defined('BASEPATH') OR exit('No direct script access allow');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('url', 'form'));
        $this->load->model(array('Restaurants_model', 'Category_model', 'Image_model'));
        $this->load->library('session');
    }

    public function index() {
        // number of restaurants to display
        define("showTopRating", 4);
        define("showhighestDiscount", 8);

        // retrieve data from model
        $restProfile = $this->Restaurants_model->getRestProfile();
        $rate = $this->Restaurants_model->getRestRating();
        $restImage = $this->Image_model->getRestImage();
        $counter = 0;
        $topRating = array();
        $highestDiscount = array();
        foreach ($restProfile as $row) {
            
            // restaurant with rate
            if (isset($rate[$counter]) && ($row->restaurantID) == ($rate[$counter]->restaurantID)) {
                $avgRating = $rate[$counter]->average;
            } else {
                $avgRating = 0;
            }
            
            // restaurant with image
            if(isset($restImage[$counter]) && ($restImage[$counter]->restaurantID) == $row->restaurantID){
                $imageAddr = $restImage[$counter]->addressImage;
            }else{
                $imageAddr = 'images/restOwner/restImage/default_restaurant.png';
            }
            
            $rprofile = array(
                'restID' => $row->restaurantID,
                'campaign' => $row->campaign,
                'address' => $row->address,
                'discount' => $row->discount,
                'restName' => $row->nameRe,
                'imageAddr' => $imageAddr,
                'average' => $avgRating
            );
            $counter++;

            array_push($topRating, $rprofile);
            array_push($highestDiscount, $rprofile);
        }

        $topRating = $this->sortRestProfile($topRating, 'average');
        $highestDiscount = $this->sortRestProfile($highestDiscount, 'discount');

        $categoriesData = $this->Category_model->getCategories();
        $data['categoriesData'] = $categoriesData;

        $data['topRating'] = $topRating;
        $data['highestDiscount'] = $highestDiscount;

        $data['limitRate'] = showTopRating;
        $data['limitDis'] = showhighestDiscount;

        // return to view
        $data['dbMsg'] = "<div class='alert alert-danger text-center'>chưa có thông tin nhà hàng!!!</div>";

        $data['content'] = 'site/home/index/index.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }

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

<?php

defined('BASEPATH') OR exit('No direct script access allow');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('url', 'form'));
        $this->load->model('Restaurants_model');
        $this->load->library('session');
    }

    public function index() {
        // sortby restaurant statement
        define("sortByAvg", "average");
        define("sortByDisc", "discount");

        // number of restaurants to display
        define("showTopRating", 4);
        define("showhighestDiscount", 8);
        // retrieve data from model
        $rate = $this->Restaurants_model->getInterestingRestaurants(sortByAvg);
        $discount = $this->Restaurants_model->getInterestingRestaurants(sortByDisc);


        $data['topRating'] = $rate;
        $data['highestDiscount'] = $discount;

        $data['limitRate'] = showTopRating;
        $data['limitDis'] = showhighestDiscount;

        // return to view
        $data['dbMsg'] = "<div class='alert alert-danger text-center'>chưa có thông tin nhà hàng!!!</div>";
        
        $data['content'] = 'site/home/index/index.phtml';
        $this->load->view('site/layout/layout.phtml', $data);

    }

}

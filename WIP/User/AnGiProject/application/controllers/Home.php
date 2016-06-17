<?php

defined('BASEPATH') OR exit('No direct script access allow');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->model('Restaurants_model');
    }

    public function index() {

        // retrieve data from model
        $rate = $this->Restaurants_model->getInterestingRestaurants('rate');
        $discount = $this->Restaurants_model->getInterestingRestaurants('discount');

        if ($this->Restaurants_model->getInterestingRestaurants('isReturn')) {
            // prepare data to view
            $data['rate'] = $rate;
            $data['discount'] = $discount;
            $data['limitRate'] = 4;
            $data['limitDis'] = 8;

            // return to view
            $data['content'] = 'site/home/index/index.phtml';
            $this->load->view('site/layout/layout.phtml', $data);
        }else{
            echo "No information exists in database!";
            die();
            $data['content'] = 'site/home/index/index.phtml';
            $this->load->view('site/layout/layout.phtml', $data);
        }
    }

}

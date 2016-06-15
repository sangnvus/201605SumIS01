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
        $rate = $this -> Restaurants_model -> getInterestingRestaurants('rate');
        
        $pro = $this->Restaurants_model->getInterestingRestaurants('promotion');
        
        // prepare data to view
        $data['rate'] = $rate;
        $data['pro'] = $pro;
        
        // return to view
        $data['content'] = 'site/home/index/index.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }

    public function search() {
        $data = array();
        $data['content'] = 'site/home/search/index.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }

}

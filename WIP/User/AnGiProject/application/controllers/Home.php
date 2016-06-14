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
        
        // array to store data
        $data = array();
        
        // --------------------- Top rate -------------------------
        $query = $this->Restaurants_model->getTopRatingRestaurant();

        // retrieve data from restaurant model
        for ($i = 0; $i < 4; $i++) {
            $data['restName' . $i] = $query[$i][1];
            $data['address' . $i] = $query[$i][2];
            $data['image' . $i] = $query[$i][3];
            $data['rate' . $i] = $query[$i][4];
        }
        
        // --------------------- End Top Rate -------------------------
        
        // --------------------- Top Promotion -------------------------
        $data['img'] = $this->Restaurants_model->getTopPromotionRestaurant();
        
        
        // --------------------- End Top Promotion -------------------------
        
        
        
        
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

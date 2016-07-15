<?php

defined('BASEPATH') OR exit('No direct script access allow');

class Search extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Search_model');
        $this->load->helper(array('form', 'url'));
    }
    
    public function index(){
        $this ->searchRestaurant();
    }

    public function searchRestaurant() {
        $searchForm = $this->input->post('btnSearch');
        if ($searchForm == 'btnSearchValue') {
            $term = $this->input->post('userTerm');
            $food = $this->Search_model->getSearchRestaurant($term);
            // if there is data return from db
            if ($food) {
                // count search result
                $counter = 0;
                foreach($food as $row){
                    $counter++;
                }
                
                $data = array();
                $data['amount'] = $counter;
                $data['keyword'] = $term;
                $data['food'] = $food;
                $data['content'] = 'site/home/search/index.phtml';
                $this->load->view('site/layout/layout.phtml', $data);
            } else {
                echo 'error occurs';
            }
        }
    }

}

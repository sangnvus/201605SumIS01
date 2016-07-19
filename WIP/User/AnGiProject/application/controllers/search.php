<?php

defined('BASEPATH') OR exit('No direct script access allow');

class Search extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Search_model');
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
    }

    public function index() {
        $this->searchRestaurant();
    }

    public function searchRestaurant() {
        $isFound = false;
        $searchForm = $this->input->post('btnSearch');
        if ($searchForm == 'btnSearchValue') {
            $term = $this->input->post('userTerm');
            $food = $this->Search_model->getSearchRestaurant($term);
            // if there is data return from db
            if ($food) {
                // count search result
                $counter = 0;
                foreach ($food as $row) {
                    $counter++;
                }
                
                $isFound = true;
                $data = array();
                $data['amount'] = $counter;
                $data['keyword'] = $term;
                $data['food'] = $food;
                $data['isFound'] = $isFound;
                $data['content'] = 'site/home/search/index.phtml';
                $this->load->view('site/layout/layout.phtml', $data);
            } else {
                $data['isFound'] = $isFound;
                $data['keyword'] = $term;
                $data['content'] = 'site/home/search/index.phtml';
                $this->load->view('site/layout/layout.phtml', $data);
            }
        }
    }

}

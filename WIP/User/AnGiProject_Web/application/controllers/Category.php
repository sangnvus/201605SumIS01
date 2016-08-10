<?php

class Category extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('session'));
        $this->load->database();
        $this->load->model('Category_model');
    }

    function index($type = 0, $ID = 0, $page = 1) {
        $districtData = $this->Category_model->getDistrict();
        $categoriesData = $this->Category_model->getCategories();
        $data['categoriesData'] = $categoriesData;
        $count = $this->Category_model->countAll($type, $ID);

        //sua so items/trang
        $pages = ceil($count / 12);

        // $config['total_rows'] = $this->Category_model->countAll($type,$ID);
        // // $a = $this->Category_model->countAll($type,$ID);
        // // print_r($a);
        // // die();
        // //http://localhost:8080/AnGiProject/Category/index/1/1
        // $config['base_url'] = base_url()."Category/index/".$type."/".$ID;
        // $config['per_page'] = 1;
        // $start=$this->uri->segment(3);

        $resData = $this->Category_model->getRes($type, $ID, $count, $page);


        $rate = array();
        foreach ($resData as $key => $row) {
            array_push($rate, $this->Category_model->getRate($row['restaurantID']));
            // $rate[] = $this->Category_model->getRate($row['restaurantID']);
        }

        $data['categoriesData'] = $categoriesData;
        $data['districtData'] = $districtData;
        $data['resData'] = $resData;
        $data['rate'] = $rate;
        $data['pages'] = $pages;
        $data['type'] = $type;
        $data['ID'] = $ID;

        //$this->load->library('pagination', $config);
        //$data['pagination'] = $this->pagination->create_links();

        $data['content'] = 'site/restaurant/Category.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }

}

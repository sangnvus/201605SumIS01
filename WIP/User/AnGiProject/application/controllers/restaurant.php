<?php

defined('BASEPATH') OR exit('No direct script access allow');

class Restaurant extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('url','form'));
        $this->load->library(array('session'));
        $this->load->database();
        $this->load->model(array('Image_model', 'Food_model','Category_model'));     
    }

    public function index($restID = 2) {
        $food = $this -> Food_model -> getFood($restID);
        
        define("dishToShow", 20);
        
        $data = array();
        $data['food'] = $food;
        $data['limitDisplay'] = dishToShow;
        
        $data['content'] = 'site/restaurant/index.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }

    public function category($type = 0,$ID = 0,$page = 1)
    {
        
        $districtData = $this->Category_model-> getDistrict();
        $categoriesData = $this->Category_model-> getCategories();
        $count = $this->Category_model->countAll($type,$ID);
        //sua so items/trang
        $pages = ceil($count/1);
        $resData = $this->Category_model->getRes($type,$ID,$count,$page);
        $rate = array();
        foreach ($resData as $key => $row)
        {
            $rate[] = $this->Category_model->getRate($row['restaurantID']);
        }
        $data['categoriesData'] = $categoriesData;
        $data['districtData'] = $districtData;
        $data['resData'] = $resData;
        $data['rate'] = $rate;
        $data['pages'] = $pages;
        $data['type'] = $type;
        $data['ID'] = $ID;
        $data['content'] = 'site/restaurant/Category.phtml';
        $this->load->view('site/layout/layout.phtml',$data);

    }

    public function view($restUrl) {
        $data['restUrl'] = $restUrl;
        $data['content'] = 'site/restaurant/view.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }

    public function Restaurant_Banner() {
        $data['content'] = 'site/user/restaurant_owner/Rbanner.phtml';
            $this->load->view('site/layout/layout.phtml', $data);
    }

    public function Restaurant_infor() {
        $data = array();
        $categoriesData = $this->Category_model-> getCategories();
        $data['categoriesData'] = $categoriesData;
        $data['content'] = 'site/user/restaurant_owner/Rinfor.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }

    public function Restaurant_price() {
        $data = array();
        $data['content'] = 'site/user/restaurant_owner/Rprice.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }
}

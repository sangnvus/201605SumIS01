<?php

defined('BASEPATH') OR exit('No direct script access allow');

class Restaurant extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('url','form'));
        $this->load->library(array('session','form_validation'));
        $this->load->database();
        $this->load->model(array('Image_model', 'Food_model','Category_model','restaurants_model','User_model'));     
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
        $userID = $this->session->userdata("ID");
        if($userID == null){
            redirect(base_url());
            return;
        }

        $categoriesData = $this->Category_model-> getCategories();
        $resData = $this->restaurants_model-> getResByUser($userID);
        $data['categoriesData'] = $categoriesData;
        $data['resData'] = $resData;
        $data['addressData'] =  $this->User_model->getAddress($resData[0]['addressID']);
        $data['province'] = $this->User_model->getData('province');
        $data['district'] = $this->User_model->getData('district');
        $data['ward'] =  $this->User_model->getData('ward');
        $data['content'] = 'site/user/restaurant_owner/Rinfor.phtml';

                //set validation rules
        $this->form_validation->set_rules('nameRe', 'Tên nhà hàng', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('favouriteFood', 'Món đặc sắc', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('spaceRes', 'Không gian nhà hàng', 'trim|required|min_length[3]');

        if($this->form_validation->run() == FALSE){
            $this->load->view('site/layout/layout.phtml', $data);
        }
        else{  
            $data = array(
            'nameRe' => $this->input->post('nameRe'),
            'favouriteFood' => $this->input->post('favouriteFood'),
            'minPrice' => $this->input->post('minPrice'),
            'maxPrice' => $this->input->post('maxPrice'),
            'spaceRes' => $this->input->post('spaceRes'),
            'carParkingRes' => $this->input->post('carParkingRes'),
            'openTimeRe' => $this->input->post('openTimeRe'),
            'closeTimeRe' => $this->input->post('closeTimeRe'),
            'discount' => $this->input->post('discount'),
            'otherPoints' => $this->input->post('otherPoints'),
            );
            // print_r($data);
            // die();

            $address = array(
            'address'=> $this->input->post('address'),
            'provinceid' => $this->input->post('province'),
            'districtid' => $this->input->post('district'),
            'wardid' => $this->input->post('ward'),
                );

            if ($this->restaurants_model->updateResInfo($resData[0]['restaurantID'],$data))
            {
                    $this->session->set_flashdata('msgResInfo','<div class="alert alert-success text-center">Thành công !!</div>');
                     //   redirect('restaurant');

            }
            else
            {
                // error
                $this->session->set_flashdata('msgResInfo','<div class="alert alert-danger text-center">Thất bại, kiểm tra thông tin!!!</div>');
                     //   redirect('restaurant');

            }

            if ($this->User_model->updateAddress($resData[0]['addressID'] ,$address))
            {
                    $this->session->set_flashdata('msgResInfo','<div class="alert alert-success text-center">Thành công !!</div>');
                    redirect('restaurant/restaurant_infor');
            }
            else
            {
                // error
                $this->session->set_flashdata('msgResInfo','<div class="alert alert-danger text-center">Thất bại, kiểm tra địa chỉ !!!</div>');
                redirect('restaurant/restaurant_infor');
            }
        }

    }

    public function Restaurant_price() {
        $data = array();
        $data['content'] = 'site/user/restaurant_owner/Rprice.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }
}

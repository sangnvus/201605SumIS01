<?php
class Category extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form','url','date'));
		$this->load->library(array('session', 'form_validation', 'email'));
		$this->load->database();
		$this->load->model('Category_model');
	}
	
	function index($type = 0,$ID = 0)
	{
		$data['content'] = 'site/restaurant/Category.phtml';
		//echo "string".$type."ID =".$ID;
		$districtData = $this->Category_model-> getDistrict();
        $categoriesData = $this->Category_model-> getCategories();
        //$resData = null;

        if($type != 2){
        	$resData = $this->Category_model->getResByCate($ID);
        }
        else{
        	$resData = $this->Category_model->getResByLocal($ID);
        }
        
        $rate = array();
        foreach ($resData as $key => $row)
        {
        	$rate[] = $this->Category_model->getRate($row['restaurantID']);

        }
        //print_r($rate);       
        $data['categoriesData'] = $categoriesData;
        $data['districtData'] = $districtData;
        $data['resData'] = $resData;
        $data['rate'] = $rate;
        $this->load->view('site/layout/layout.phtml',$data);

    }

}
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
	
	function index($local= 0)
	{
		$data['content'] = 'site/restaurant/Category.phtml';

		$districtData = $this->Category_model-> getDistrict($local);
        
        $resData = $this->Category_model->getRestaurant($local);
        foreach ($resData as $key => $row)
        {
        
        	$rate = $this->Category_model->getRate($row['restaurantID']);
        	print_r($rate);
        }
        $data['districtData'] = $districtData;
        $data['resData'] = $resData;
        
        $this->load->view('site/layout/layout.phtml',$data);

    }

}
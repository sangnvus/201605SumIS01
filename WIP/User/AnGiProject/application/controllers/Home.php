<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
  
class Home extends CI_Controller {

	 public function __construct(){
        parent::__construct();
         $this->load->helper('url');
         $this->load->library('session');
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
       		// inform user that db does not exist, (NEED TO UPDATE UI)
            echo "No information exists in database!";
            die();
            $data['content'] = 'site/home/index/index.phtml';
            $this->load->view('site/layout/layout.phtml', $data);
        }
    }

    public function search() {
        $data = array();
        $data['content'] = 'site/home/search/index.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }
}

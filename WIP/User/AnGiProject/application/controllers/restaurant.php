<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
  
class Restaurant extends CI_Controller {

	 public function __construct(){
        parent::__construct();
         $this->load->helper('url');
         $this->load->library('session');
    }
  
    public function category() {
        $data = array();
        $data['content'] = 'site/restaurant/category/category.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }

}
<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
  
class Home extends CI_Controller {

	 public function __construct(){
        parent::__construct();
         $this->load->helper('url');
         $this->load->library('session');
    }
  
    public function index() {
        $data = array();
        $data['content'] = 'site/home/index/index.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }

    public function search() {
        $data = array();
        $data['content'] = 'site/home/search/index.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }
}
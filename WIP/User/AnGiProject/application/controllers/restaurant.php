<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Restaurant extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('url', 'form'));
        $this->load->library('session');
    }

    public function index() {
        $data = array();
        $data['content'] = 'site/restaurant/index.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }

    public function category() {
        $data = array();
        $data['content'] = 'site/restaurant/category.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }

    public function view($restUrl) {
        $data['restUrl'] = $restUrl;
        $data['content'] = 'site/restaurant/view.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }
    
    public function Restaurant_Banner() {
        $data = array();
        $data['content'] = 'site/restaurant/Rbanner.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }

    public function Restaurant_infor() {
        $data = array();
        $data['content'] = 'site/restaurant/Rinfor.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }

    public function Restaurant_price() {
        $data = array();
        $data['content'] = 'site/restaurant/Rprice.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }
    
}

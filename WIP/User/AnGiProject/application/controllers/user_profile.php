<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class user_profile extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function index() {
        $data = array();
        $data['content'] = 'site/user/profile/index.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }

    public function change_Password() {
        $data = array();
        $data['content'] = 'site/user/profile/changePassword.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }

    public function Restaurant_Banner() {
        $data = array();
        $data['content'] = 'site/user/profile/Rbanner.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }

    public function Restaurant_infor() {
        $data = array();
        $data['content'] = 'site/user/profile/Rinfor.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }

    public function Restaurant_price() {
        $data = array();
        $data['content'] = 'site/user/profile/Rprice.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }
}
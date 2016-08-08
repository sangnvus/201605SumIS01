<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class booking extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function user_booking_history(){
        $data = array();
        $data['content'] = 'site/booking/user_history_booking.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }


}
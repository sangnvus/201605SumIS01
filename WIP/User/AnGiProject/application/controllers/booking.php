<?php

defined('BASEPATH') OR exit('No direct script access allow');

class Booking extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url', 'form');
        $this->load->database();
        $this->load->model('Booking_model');
    }

    public function index() {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date('Y-m-d H:i:s');
        $bookDate = $this->input->post('dateBooking');
        // check to receive service date
        if($bookDate < $today){
            echo 'You cannot enter the passed date!';
        }
        //user id will be from login process
        $userID = 2;
        $data = array(
            'dateCreateBo' => $today,
            'quantityMember' => $this->input->post('numPeople'),
            'dateBooking' => $bookDate, 
            'commentBo' => $this -> input -> post('bcomment'),
            'restaurantID' => $this->input->post('restaurantID'),
            'userID' => $userID
        );
        // insert booking data into database
        if ($this->Booking_model->insertReservation($data)) {
            echo 'You have booked with our restaurant';
            die();
        }
    }

    public function reserve($restID) {
        $data['restID'] = $restID;
        $data['content'] = 'site/booking/index.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }

}

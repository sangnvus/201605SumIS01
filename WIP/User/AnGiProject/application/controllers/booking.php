<?php

defined('BASEPATH') OR exit('No direct script access allow');

class Booking extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('url', 'form'));
        $this->load->database();
        $this->load->model(array('Booking_model', 'User_model', 'Restaurants_model'));
        $this->load->model('Restaurants_model');
        $this->load->library(array('session', 'email'));
    }

//    public function testEmail() {
//        $config = Array(
//            'protocol' => 'smtp',
//            'smtp_host' => 'ssl://smtp.googlemail.com',
//            'smtp_port' => 465,
//            'smtp_user' => 'soulivongfc@gmail.com', // change it to yours
//            'smtp_pass' => '123Unique', // change it to yours
//            'mailtype' => 'html',
//            'charset' => 'iso-8859-1',
//            'wordwrap' => TRUE
//        );
//
//        $message = 'test codeIgniter email';
//        $this->load->library('email', $config);
//        $this->email->set_newline("\r\n");
//        $this->email->from('soulivongfc@gmail.com'); // change it to yours
//        $this->email->to('soulivongse03451@fpt.edu.vn'); // change it to yours
//        $this->email->subject('Resume from JobsBuddy for your Job posting');
//        $this->email->message($message);
//        if ($this->email->send()) {
//            echo 'Email sent.';
//        } else {
//            show_error($this->email->print_debugger());
//        }
//    }
    // when user click on booking link
    public function reserve($restID) {
        $data['restID'] = $restID;
        $data['content'] = 'site/booking/index.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }

    // when user click save in booking page
    public function makeReservation($userID) {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date('Y-m-d H:i:s');
        $bookDate = $this->input->post('dateBooking');
        $bookingTime = $this->input->post('bookingTime');

        // check to receive service date
        if ($bookDate < $today) {
            echo 'You cannot enter the passed date!';
        }
        //user id will be from login process
        $data = array(
            'dateCreateBo' => $today,
            'quantityMember' => $this->input->post('numPeople'),
            'dateBooking' => $bookDate,
            'bookingTime' => $bookingTime,
            'commentBo' => $this->input->post('bcomment'),
            'restaurantID' => $this->input->post('restaurantID'),
            'userID' => $userID
        );
        // insert booking data into database
        if ($this->Booking_model->insertReservation($data)) {

//            $userInfo = $this->User_model->getUser($userID);
//            $restInfo = $this->Restaurants_model->getRestOwner($restID);
//            $customerEmail = $userInfo[0]['emailUser'];
//            $customerName = $userInfo[0]['firstNameUser'] . ' - ' . $userInfo[0]['lastNameUser'];
//            $restOwnerEmail = $restInfo[0]['emailUser'];
//            $customerEmail = "soulivongfc@gmail.com";
//            $customerName = "Soulivong";
//            $restOwnerEmail = "soulivongse03451@fpt.edu.vn";
//            $messages = "booking date: $bookDate \n" .
//                    "number of people: $this->input->post('numPeople') \n" .
//                    "comment: $this->input->post('restaurantID')";
            // inform restaurant owner
//            $this->emailToRestOwner($customerEmail, $customerName, $restOwnerEmail, $messages);
            redirect('booking/viewBooking/' . $userID);
        } else {
            echo 'Errors occur cannot make a reservation!';
            redirect();
        }
    }

    // send email to restaurant owner when there's a booking
    function emailToRestOwner($from_email, $name, $to_email, $messages) {
        $subject = "customer make a reservation";

        //Load email library 
        $this->load->library('email');

        $this->email->from($from_email, $name);
        $this->email->to($to_email);
        $this->email->subject($subject);
        $this->email->message($messages);

        //Send mail 
        if ($this->email->send()) {
            echo "Email sent successfully.";
        } else {
            echo "Error in sending Email.";
        }
    }

    // restaurant owner view booking list
    public function manageReservation() {
        // check if user update booking status
        // ------------------------------------
        $isUpdate = false;
        $formSubmit = $this->input->post('manageBooking');
        if( $formSubmit != null ){
            $bid = $this -> input -> post('bid');
            $status = $this->input->post('bookingStatus');

            if ($this->Booking_model->updateReservation($status, $bid)) {
                $this->session->set_flashdata('msgUpdateBooking', '<div class="alert alert-success">Lưu thành công !!</div>');            

                $isUpdate = true;
            } else {
                $this->session->set_flashdata('msgUpdateBooking', '<div class="alert alert-danger">Lưu thất bại !!</div>');            
                $data['isUpdate'] = $isUpdate;
                $isUpdate = false;
            }
        }
        // ------------------------------------
        $isOrder = false;
        $restID = $this->session->userdata("ID");
        if ($restID == null) {
            redirect(base_url());
            return;
        }
        $blist = $this->Booking_model->getBookingList($restID);
        // if data exist in db
        if ($blist) {
            // if blist -> status = 0 => waiting, 1 => served, and 2 => cancelled
            $data['list'] = array();
            foreach ($blist as $row) {
                if ($row->statusBo == 1) {
                    $statusText = 'Served';
                    // remaining dropdownlist values
                    $dropValue1 = 0;
                    $dropText1 = 'Waiting';
                    $dropValue2 = 2;
                    $dropText2 = 'Cancelled';
                } else if ($row->statusBo == 2) {
                    $statusText = 'Cancelled';
                    // remaining dropdownlist values
                    $dropValue1 = 0;
                    $dropText1 = 'Waiting';
                    $dropValue2 = 1;
                    $dropText2 = 'Served';
                } else {
                    $statusText = 'Waiting';
                    // remaining dropdownlist values
                    $dropValue1 = 1;
                    $dropText1 = 'Served';
                    $dropValue2 = 2;
                    $dropText2 = 'Cancelled';
                }

                $brlist = array(
                    'bookingID' => $row->bookingID,
                    'dateCreateBo' => $row->dateCreateBo,
                    'userID' => $row->userID,
                    'phoneUser' => $row->phoneUser,
                    'quantityMember' => $row->quantityMember,
                    'dateBooking' => $row->dateBooking,
                    'request' => $row->commentBo,
                    'statusText' => $statusText,
                    // remaining dropdownlist values
                    'dropValue1' => $dropValue1,
                    'dropText1' => $dropText1,
                    'dropValue2' => $dropValue2,
                    'dropText2' => $dropText2
                );
                array_push($data['list'], $brlist);
            }

            $isOrder = true;
            $data['isOrder'] = $isOrder;
            $data['isUpdate'] = $isUpdate;
            $data['content'] = 'site/user/restaurant_owner/restaurant_manage_booking.phtml';
            $this->load->view('site/layout/layout.phtml', $data);
        } else {
            $this->session->set_flashdata('msgOrder', '<div class="alert alert-danger">Chưa có đơn hàng nào !!</div>');
            $data['isOrder'] = $isOrder;
            $data['content'] = 'site/user/restaurant_owner/restaurant_manage_booking.phtml';
            $this->load->view('site/layout/layout.phtml', $data);
        }
    }

    // customers view thier booking lists
    public function viewBooking() {
        $userID = $this->session->userdata("ID");
        if($userID == null){
            redirect(base_url());
            return;
         }
 
        $blist = $this->Booking_model->getCustomerBookingList($userID);
        $isBooked = false;
        // if users have maded reservation
        if ($blist) {
            $brid = $this->Booking_model->getBookRestId($userID);
            $data['list'] = array();
            foreach ($brid as $row) {
                $rating = $this->Restaurants_model->getSepecificRestaurant($row->restaurantID);
                foreach ($blist as $r) {
                    if ($r->restaurantID == $rating->restaurantID) {
                        if ($rating->average != null || !empty($rating->average)) {
                            $avgRating = $rating->average;
                        } else {
                            $avgRating = 0;
                        }
                        if ($r->statusBo == 1) {
                            $statusText = 'Served';
                        } else if ($r->statusBo == 2) {
                            $statusText = 'Cancelled';
                        } else {
                            $statusText = 'Waiting';
                        }
                        $bclist = array(
                            'bid' => $r->bookingID,
                            'id' => $r->restaurantID,
                            'addressImage' => $rating->addressImage,
                            'nameRe' => $rating->nameRe,
                            'discount' => $rating->discount,
                            'campaign' => $rating->descriptionRes,
                            'address' => $rating->address,
                            'serveDate' => $r->dateBooking,
                            'bookingTime' => $r->bookingTime,
                            'quantityMember' => $r->quantityMember,
                            'statusText' => $statusText,
                            'avgRating' => $avgRating
                        );
                        if (!in_array($bclist, $data['list'])) {
                            array_push($data['list'], $bclist);
                        }
                    }
                }
            }

            // sort data by dateBooking in descending order
            usort($data['list'], array(__CLASS__, 'sortByDateDesc'));
            $isBooked = true;
            $data['isBooked'] = $isBooked;
            $data['content'] = 'site/booking/user_history_booking.phtml';
            $this->load->view('site/layout/layout.phtml', $data);
        } else {
            // echo 'You have not performed any reservation!';
            $this->session->set_flashdata('msgBooking', '<div class="alert alert-danger">Bạn chưa đặt chỗ bao giờ !!</div>');
            $data['isBooked'] = $isBooked;
            $data['content'] = 'site/booking/user_history_booking.phtml';
            $this->load->view('site/layout/layout.phtml', $data);
        }
    }

    // sort by datebooking function in descending order
    function sortByDateDesc($a, $b) {
        if ($a['serveDate'] == $b['serveDate']) {
            return 0;
        }
        return $a['serveDate'] > $b['serveDate'] ? -1 : 1;
    }

    // when restaurant update booking from restaurant_manage_booking page
    public function updateBooking($bid) {
        $status = $this->input->post('bookingStatus');
        $isUpdate = false;
        if ($this->Booking_model->updateReservation($status, $bid)) {
            $this->session->set_flashdata('msgUpdateBooking', '<div class="alert alert-success">Lưu thành công !!</div>');            

            $isUpdate = true;
            $data['isUpdate'] = $isUpdate;
            $data['content'] = 'site/user/restaurant_owner/restaurant_manage_booking.phtml';
            $this->load->view('site/layout/layout.phtml', $data);
        } else {
            $this->session->set_flashdata('msgUpdateBooking', '<div class="alert alert-danger">Lưu thất bại !!</div>');            
            $data['isUpdate'] = $isUpdate;
            $data['content'] = 'site/user/restaurant_owner/restaurant_manage_booking.phtml';
            $this->load->view('site/layout/layout.phtml', $data);

            echo 'Errors!';
        }
    }

}

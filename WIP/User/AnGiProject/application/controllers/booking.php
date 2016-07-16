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

    // when user click on booking link
    public function reserve($restID) {
        $data['restID'] = $restID;
        $data['content'] = 'site/booking/index.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }

    // when user click save in booking page
    public function makeReservation($userID = 2, $restID = 2) {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date('Y-m-d H:i:s');
        $bookDate = $this->input->post('dateBooking');
        // check to receive service date
        if ($bookDate < $today) {
            echo 'You cannot enter the passed date!';
        }
        //user id will be from login process
        $data = array(
            'dateCreateBo' => $today,
            'quantityMember' => $this->input->post('numPeople'),
            'dateBooking' => $bookDate,
            'commentBo' => $this->input->post('bcomment'),
            'restaurantID' => $this->input->post('restaurantID'),
            'userID' => $userID
        );
        // insert booking data into database
        if ($this->Booking_model->insertReservation($data)) {

            $userInfo = $this->User_model->getUser($userID);
            $restInfo = $this->Restaurants_model->getRestOwner($restID);

//            $customerEmail = $userInfo[0]['emailUser'];
//            $customerName = $userInfo[0]['firstNameUser'] . ' - ' . $userInfo[0]['lastNameUser'];
//            $restOwnerEmail = $restInfo[0]['emailUser'];
  
            $messages = "booking date: $bookDate \n" .
                    "number of people: $this->input->post('numPeople') \n" .
                    "comment: $this->input->post('restaurantID')";

            // inform restaurant owner
//            $this->emailToRestOwner($customerEmail, $customerName, $restOwnerEmail, $messages);
            redirect('booking/viewBooking/2');
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
    public function manageReservation($restID) {
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
                    'statusText' => $statusText,
                    // remaining dropdownlist values
                    'dropValue1' => $dropValue1,
                    'dropText1' => $dropText1,
                    'dropValue2' => $dropValue2,
                    'dropText2' => $dropText2
                );
                array_push($data['list'], $brlist);
            }

            $data['content'] = 'site/user/restaurant_owner/restaurant_manage_booking.phtml';
            $this->load->view('site/layout/layout.phtml', $data);
        } else {
            echo 'No data in database';
        }
    }

    // customers view thier booking lists
    public function viewBooking($userID = 2) {
        // assume user id = 2
        $blist = $this->Booking_model->getCustomerBookingList($userID);
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

            $data['content'] = 'site/booking/user_history_booking.phtml';
            $this->load->view('site/layout/layout.phtml', $data);
        } else {
            echo 'You have not performed any reservation!';
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
        if ($this->Booking_model->updateReservation($status, $bid)) {
            echo 'updated!';
        } else {
            echo 'Errors!';
        }
    }

}

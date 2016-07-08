<?php

defined('BASEPATH') OR exit('No direct script access allow');

class Booking extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('url', 'form'));
        $this->load->database();
        $this->load->model('Booking_model');
        $this->load->model('Restaurants_model');
        $this->load->library('session');
    }

    // when user click save in booking page
    public function makeReservation($userID) {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date('Y-m-d H:i:s');
        $bookDate = $this->input->post('dateBooking');
        // check to receive service date
        if ($bookDate < $today) {
            echo 'You cannot enter the passed date!';
            die();
        }
        //user id will be from login process
        $data = array(
            'dateCreateBo' => $today,
            'quantityMember' => $this->input->post('numPeople'),
            'dateBooking' => $bookDate,
            'commentBo' => $this->input->post('bcomment'),
            'restaurantID' => $this->input->post('restaurantID'),
            'userID' => $userID,
            'content' => 'page_to_display'
        );
        // insert booking data into database
        if ($this->Booking_model->insertReservation($data)) {
            echo 'You have booked with our restaurant';
        } else {
            echo 'Errors occur cannot make a reservation!';
        }
    }

    // when user click on booking link
    public function reserve($restID) {
        $data['restID'] = $restID;
        $data['content'] = 'site/booking/index.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
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
            
            $data['content'] = 'site/booking/restaurant_manage_booking.phtml';
            $this->load->view('site/layout/layout.phtml', $data);
        } else {
            echo 'No data in database';
        }
    }

    // customers view thier booking lists
    public function viewBooking($userID) {
        // assume user id = 2
        $blist = $this->Booking_model->getCustomerBookingList($userID);
        // if users have maded reservation
        if ($blist) {
            $brid = $this->Booking_model->getBookRestId($userID);
            $data['list'] = array();
            foreach ($brid as $row) {
                $rating = $this->Restaurants_model->getRestaurantRating($row->restaurantID);
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
                            'addressImage' => $r->addressImage,
                            'nameRe' => $r->nameRe,
                            'discount' => $r->discount,
                            'campaign' => $r->descriptionRes,
                            'address' => $r->address,
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

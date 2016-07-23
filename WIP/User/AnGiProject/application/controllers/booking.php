<?php

defined('BASEPATH') OR exit('No direct script access allow');

class Booking extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('url', 'form'));
        $this->load->database();
        $this->load->model(array('Booking_model', 'User_model', 'Restaurants_model', 'Image_model','category_model'));
        $this->load->library(array('session', 'email'));

        // statusBo 0 waiting, 1 served, 2 cancelled
    }

    // when user click on booking link
    public function reserve($restID) {
        $data['restID'] = $restID;
        $resData = $this->Restaurants_model->getResByResID($restID);
        $data['resData'] = $resData;
        $resCate = $this->Restaurants_model->getResCate($restID); 
        $data['resCate'] = $resCate;
        $addressData = $this->User_model->getAddress($resData[0]['addressID']);
        $data['addressData'] =  $addressData;
        $data['rateData'] =  $this->category_model->getRate($restID);
        $data['bannerData'] = $this->Image_model->getBannerByResID($resData[0]['restaurantID']);        
        // print_r($data['bannerData']);
        // die();
        $data['content'] = 'site/restaurant/view.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }

    // when user click save in booking page
    public function makeReservation() {
        $userID = $this->session->userdata("ID");

        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date('Y-m-d H:i:s');
        $bookDate = $this->input->post('dateBooking');
        $bookingTime = $this->input->post('bookingTime');

        // check to receive service date
        if ($bookDate < $today) {
            $this->session->set_flashdata('dateErrorMsg', '<div class="alert alert-danger">You cannot enter the passed date!!!</div>');
            $userID = $this->input->post('restaurantID');
            redirect('booking/reserve/' . $userID);
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
            redirect('booking/viewBooking/' . $userID);
        } else {
            $this->session->set_flashdata('dbBookingError', '<div class="alert alert-danger">database errors!!!</div>');
            redirect('booking/viewBooking/' . $userID);
        }
    }

    // restaurant owner view booking list
    public function manageReservation() {
        // check if user update booking status
        // ------------------------------------
        $isUpdate = null;
        $formSubmit = $this->input->post('manageBooking');
        if ($formSubmit != null) {
            $bid = $this->input->post('bid');
            $status = $this->input->post('bookingStatus');

            if ($this->Booking_model->updateReservation($status, $bid)) {
                $this->session->set_flashdata('msgUpdateBooking', '<div class="alert alert-success">Lưu thành công !!</div>');
                $isUpdate = true;
            } else {
                $this->session->set_flashdata('msgUpdateBooking', '<div class="alert alert-danger">Lưu thất bại !!</div>');
                $isUpdate = false;
                $data['isUpdate'] = $isUpdate;
            }
        }
        // ------------------------------------
        $isOrder = false;
        $userID = $this->session->userdata("ID");
        if ($userID == null) {
            redirect(base_url());
            return;
        }

        $rest = -1;
        $restaurant = $this->Booking_model->getRestID($userID);
        foreach ($restaurant as $row) {
            $rest = $row->restaurantID;
        }
        $blist = $this->Booking_model->getBookingList($rest);
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
                    'bookingTime' => $row->bookingTime,
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
            $data['isUpdate'] = $isUpdate;
            $data['content'] = 'site/user/restaurant_owner/restaurant_manage_booking.phtml';
            $this->load->view('site/layout/layout.phtml', $data);
        }
    }

    // customers view thier booking lists
    public function viewBooking() {
        $userID = $this->session->userdata("ID");
        if ($userID == null) {
            redirect(base_url());
            return;
        }

        $blist = $this->Booking_model->getCustomerBookingList($userID);
        $rate = $this->Restaurants_model->getRestRating();
        $restImage = $this->Image_model->getRestImage();
        $isBooked = false;
        // if users have made reservation
        if ($blist) {
            $custBookList = array();
            $counter = 0;
            foreach ($blist as $row) {
                if (isset($rate[$counter]) && ($row->restaurantID) == ($rate[$counter]->restaurantID) && ($restImage[$counter]->restaurantID) == $row->restaurantID) {
                    $avgRating = $rate[$counter]->average;
                } else {
                    $avgRating = 0;
                }
                if ($row->statusBo == 1) {
                    $statusText = 'Served';
                } else if ($row->statusBo == 2) {
                    $statusText = 'Cancelled';
                } else {
                    $statusText = 'Waiting';
                }
                $bclist = array(
                    'bid' => $row->bookingID,
                    'id' => $row->restaurantID,
                    'addressImage' => $restImage[$counter] -> addressImage,
                    'nameRe' => $row->nameRe,
                    'discount' => $row->discount,
                    'campaign' => $row->descriptionRes,
                    'address' => $row->address,
                    'serveDate' => $row->dateBooking,
                    'bookingTime' => $row->bookingTime,
                    'quantityMember' => $row->quantityMember,
                    'statusText' => $statusText,
                    'avgRating' => $avgRating
                );
                array_push($custBookList, $bclist);
            }

            // sort data by dateBooking in descending order
            usort($custBookList, array(__CLASS__, 'sortByDateDesc'));

            $isBooked = true;
            $data['isBooked'] = $isBooked;
            $data['content'] = 'site/booking/user_history_booking.phtml';
            $this->load->view('site/layout/layout.phtml', $data);
        } else {
            // when user have not performed any reservation
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
//    public function updateBooking($bid) {
//        $status = $this->input->post('bookingStatus');
//        $isUpdate = false;
//        if ($this->Booking_model->updateReservation($status, $bid)) {
//            $this->session->set_flashdata('msgUpdateBooking', '<div class="alert alert-success">Lưu thành công !!</div>');            
//
//            $isUpdate = true;
//            $data['isUpdate'] = $isUpdate;
//            $data['content'] = 'site/user/restaurant_owner/restaurant_manage_booking.phtml';
//            $this->load->view('site/layout/layout.phtml', $data);
//        } else {
//            $this->session->set_flashdata('msgUpdateBooking', '<div class="alert alert-danger">Lưu thất bại !!</div>');            
//            $data['isUpdate'] = $isUpdate;
//            $data['content'] = 'site/user/restaurant_owner/restaurant_manage_booking.phtml';
//            $this->load->view('site/layout/layout.phtml', $data);
//
//            echo 'Errors!';
//        }
//    }
}

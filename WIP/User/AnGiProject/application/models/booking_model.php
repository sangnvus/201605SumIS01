<?php

class Booking_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // for restaurant owner
    function getBookingList($restID) {
        $query = $this->db->query(' SELECT * FROM booking b, Users u ' .
                ' WHERE b.userID = u.userID AND restaurantID = ' . $restID . ';');
        if ($query->num_rows() > 0) {
            $data = $query->result();
            return $data;
        } else {
            return NULL;
        }
    }

    // for customer
    function getCustomerBookingList($userID) {
        $sql = " SELECT bookingID, b.restaurantID, addressImage, nameRe, descriptionRes, discount, address, dateBooking, quantityMember, statusBo " .
                " FROM restaurants r, booking b, Users u, images i, food f, address addr " .
                " WHERE r.restaurantID = b.restaurantID AND r.restaurantID = f.restaurantID " .
                " AND f.imageID = i.imageID AND r.addressID = addr.addressID AND b.userID = u.userID AND b.userID = " . $userID .
                " ORDER BY dateBooking DESC; ";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $data = $query->result();
            return $data;
        } else {
            return NULL;
        }
    }

    //  get restaurant id in booking table with a specific user
    function getBookRestId($userId) {
        $sql = " SELECT b.restaurantID FROM booking b, Users u, restaurants r " .
                " WHERE b.userID = u.userID AND b.restaurantID = r.restaurantID AND b.userID = " . $userId .
                " ORDER BY dateBooking DESC; ";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $data = $query->result();
            return $data;
        } else {
            return NULL;
        }
    }

    // when user make a reservation
    function insertReservation($data) {
        return $this->db->insert('booking', $data);
    }

    // restaurant owner update booking status
    function updateReservation($status, $bid){
        $sql = " UPDATE Booking " . 
               " SET statusBo = $status " .
               " WHERE bookingID = $bid; ";
        $this -> db -> query($sql);
        if($this->db->affected_rows() == '1'){
            return true;
        }else{
            return false;
        }
    }
}

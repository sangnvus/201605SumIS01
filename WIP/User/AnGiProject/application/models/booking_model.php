<?php

class Booking_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // for restaurant owner
    function getBookingList($restID) {
        $sql = " SELECT * FROM restaurants r, booking b " .
                " WHERE b.restaurantID = r.restaurantID AND b.restaurantID = ".$restID.";";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $data = $query->result();
            return $data;
        } else {
            return NULL;
        }
    }

    // for customer
    function getCustomerBookingList($userID) {
        $sql = " SELECT * FROM booking b, users u " .
                " WHERE b.userID = u.userID AND b.userID = " . $userID . "; ";

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
    function updateReservation($status, $bid) {
        $sql = " UPDATE Booking " .
                " SET statusBo = $status " .
                " WHERE bookingID = $bid; ";
        $this->db->query($sql);
        if ($this->db->affected_rows() == '1') {
            return true;
        } else {
            return false;
        }
    }

}

<?php

class Booking_model extends CI_Model {

    function __construct() {
        parent::__construct();
        // statusBo 0 waiting, 1 served, 2 cancelled
    }

    // get restaurant id
    function getRestID($userID) {
        $sql = " SELECT r.restaurantID FROM restaurants r, users u " .
                " WHERE r.userID = u.userID AND authorityUser = 2 AND r.userID = ?; ";
        $query = $this -> db -> query($sql, array($userID));
        return $query -> result();
    }

    // for restaurant owner
    function getBookingList($restID) {
        $sql = " SELECT * FROM restaurants r, booking b, users u " .
                " WHERE b.restaurantID = r.restaurantID AND u.userID = b.userID AND authorityUser = 2 AND b.restaurantID = " . $restID . ";";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $data = $query->result();
            return $data;
        } else {
            return NULL;
        }
    }

    // for customer booking list
    function getCustomerBookingList($userID) {
        $sql = " SELECT * FROM booking b, users u, restaurants r " .
                " WHERE b.userID = u.userID AND b.restaurantID = r.restaurantID AND b.userID = ? " .
                " ORDER BY dateBooking DESC, bookingTime DESC;";

        $query = $this->db->query($sql, array($userID));
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

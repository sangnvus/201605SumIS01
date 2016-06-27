<?php

class Booking_model extends CI_Model{
    function __construct() {
        parent::__construct();
    }
    
    function insertReservation($data){
        return $this -> db -> insert('booking', $data);
    }
}

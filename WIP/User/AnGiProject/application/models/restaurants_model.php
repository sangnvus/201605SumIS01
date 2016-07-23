<?php

class Restaurants_model extends CI_Model {

    function __construct() {
        parent::__construct();

        // typeImage: 0 avatar, 1 restaurant avatar, 2 banner, 3 food
        // authorityUser: 1 customer, 2 restaurant owner
    }

    // return specific restaurant
    function getResByUser($userID) {
        $this->db->select('*');
        $this->db->from('restaurants r');
        $this->db->join('address a', 'r.addressID = a.addressID');
        $this->db->where('r.userID', $userID);
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }

    // return all restaurants' profile information   
    public function getRestProfile() {
        $sql = " SELECT r.restaurantID, campaign, address, discount, nameRe " . 
                " FROM restaurants r, address addr, users u " .
                " WHERE r.addressID = addr.addressID AND r.userID = u.userID  AND authorityUser = 2; ";

        $query = $this->db->query($sql);
        $data = $query->result();
        return $data;
    }

    // return all restaurants' rating
    function getRestRating() {
        $sql = " SELECT a.restaurantID, AVG(rateValue) AS average " .
                " FROM rate a, restaurants b " .
                " WHERE a.restaurantID = b.restaurantID " .
                " GROUP BY restaurantID; ";

        $query = $this->db->query($sql);
        $data = $query->result();
        return $data;
    }

    function insertNewRes($userID) {
        $data = array(
            'address' => 'Địa chỉ nhà hàng mới',
        );
        $this->db->insert('address', $data);
        $addressID = $this->db->insert_id();
        $data2 = array(
            'addressID' => $addressID,
            'userID' => $userID,
        );
        return $this->db->insert('restaurants', $data2);
    }

    function updateResInfo($userID, $data) {
        $this->db->where('userID', $userID);
        return $this->db->update("restaurants", $data);
    }

}

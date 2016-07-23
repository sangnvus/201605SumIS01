<?php

class Restaurants_model extends CI_Model {

    function __construct() {
        parent::__construct();

        // typeImage: 0 avatar, 1 restaurant avatar, 2 banner, 3 food
        // authorityUser: 1 customer, 2 restaurant owner
    }

    // return specific restaurant
    function getResByResID($resID) {
        $this->db->select('*');
        $this->db->from('restaurants r');
        $this->db->join('address a', 'r.addressID = a.addressID', 'left');
        $this->db->where('r.restaurantID', $resID);
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }

    function getResByUser($userID) {
        $this->db->select('*');
        $this->db->from('restaurants r');
        $this->db->join('address a', 'r.addressID = a.addressID', 'left');
        $this->db->where('r.userID', $userID);
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }

    function getResCate($resID){
        $this->db->select('*');
        $this->db->from('restaurantcategories rc');
        $this->db->join('categoriesofrestaurant cr', 'rc.categoryOfResID = cr.categoryOfResID', 'left');
        $this->db->where('restaurantID', $resID);
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
        //insert new empty address for res
        $data = array(
            'address' => 'Địa chỉ nhà hàng mới',
        );
        $this->db->insert('address', $data);
        $addressID = $this->db->insert_id();
        //insert new res with empty address
        $data2 = array(
            'addressID' => $addressID,
            'userID' => $userID,
        );
        return $this->db->insert('restaurants', $data2);
    }

    function insertResCate($resID,$bundleCate){
        // $checked = implode(',',$bundleCate);
        // Delete all old checked
        $this->db->where("restaurantID",$resID);
        $this->db->delete('restaurantcategories');
        // insert all new user's choice
        foreach ($bundleCate as $key => $value) {
            $data = array(
            'restaurantID' => $resID,
            'categoryOfResID' => $value,
            );
            $this->db->insert('restaurantcategories', $data);
        }
    }

    function updateResInfo($restaurantID, $data) {
        $this->db->where('restaurantID', $restaurantID);
        return $this->db->update("restaurants", $data);
    }

}

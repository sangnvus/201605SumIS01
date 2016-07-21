<?php

class Restaurants_model extends CI_Model {

    function __construct() {
        parent::__construct();
        
        // typeImage: 0 customer avatar, 1 restaurant avatar, 2 banner, 3 food
        // authorityUser: 1 customer, 2 restaurant owner
    }

    function getRestOwner($ID) {
        $sql = " SELECT * FROM restaurants r, users u " .
                " WHERE r.userID = u.userID AND authorityUser = 1 AND r.restaurantID = " . $ID . "; ";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;
    }

    // return specific restaurant
    public function getResByUser($userID){
        $this->db->select('*');
        $this->db->from('restaurants r'); 
        $this->db->join('address a', 'r.addressID = a.addressID');
        $this->db->where('r.userID',$userID);  
        $query = $this->db->get(); 
        $data=$query->result_array();
        return  $data;
    }
    
    
    // return specific restaurant
    public function getSepecificRestaurant($id) {

        $sql = " SELECT r.restaurantID, img.imageID,addressImage, nameRe, descriptionRes, nameImage, AVG(rateValue) AS average, discount, address " .
                " FROM restaurants r, images img, address addr, rate " .
                " WHERE r.restaurantID = " . $id . " AND r.addressID = addr.addressID AND rate.restaurantID = r.restaurantID " .
                " AND typeImage = 2"; 

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            $data = $query->row();
            return $data;
        } else {
            return NULL;
        }
    }

// return all restaurants information
    public function getInterestingRestaurants($sortBy) {
// retrieve all restaurants
        $sql = " SELECT r.restaurantID, img.imageID,addressImage, descriptionRes, nameRe, nameImage, AVG(rateValue) AS average, discount, address " .
                " FROM restaurants r, restaurantcategories cate, categoriesofrestaurant cateOf, images img, address addr, rate " .
                " WHERE r.addressID = addr.addressID AND rate.restaurantID = r.restaurantID AND typeImage = 2 " . 
                " GROUP BY r.restaurantID " .
                " ORDER BY " . $sortBy . " DESC; ";

        $query = $this->db->query($sql);
        $data = $query->result();
        return $data;
    }

    public function insertNewRes($userID){
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

    public function updateResInfo($userID,$data){
        //print_r($data)
        $this->db->where('userID', $userID);
        return $this->db->update("restaurants", $data);
    }

}

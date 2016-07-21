<?php

class Restaurants_model extends CI_Model {

    function __construct() {
        parent::__construct();
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

    public function updateResInfo($id,$data){
        $this->db->where('restaurantID', $id);
        return $this->db->update("restaurants", $data);
    }
    
    public function getSepecificRestaurant($id) {

        $sql = " SELECT r.restaurantID, img.imageID,addressImage, nameRe, descriptionRes, nameImage, AVG(rateValue) AS average, discount, address " .
                " FROM food f, restaurants r, images img, address addr, rate " .
                " WHERE r.restaurantID = " . $id . " AND f.restaurantID = r.restaurantID  AND f.imageID = img.imageID " .
                " AND r.addressID = addr.addressID AND rate.restaurantID = r.restaurantID; "; // -- AND typeImage='restaurantImage'

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
                " FROM food f, restaurants r, restaurantcategories cate, categoriesofrestaurant cateOf, images img, address addr, rate " .
                " WHERE f.restaurantID = r.restaurantID AND f.imageID = img.imageID AND r.addressID = addr.addressID " .
                " AND rate.restaurantID = r.restaurantID " . // -- AND typeImage = 'restaurantsImage'
                " GROUP BY r.restaurantID " .
                " ORDER BY " . $sortBy . " DESC; ";

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            $data = $query->result();
            return $data;
        } else {
            return NULL;
        }
    }

}

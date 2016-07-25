<?php

class Restaurants_model extends CI_Model {

    function __construct() {
        parent::__construct();

// typeImage: 0 avatar, 1 restaurant image, 2 banner, 3 food
// authorityUser: 1 customer, 2 restaurant owner
    }

// return specific restaurant
    function getResByUser($userID) {
        $this->db->select('*');
        $this->db->from('restaurants r');
        $this->db->join('address a', 'r.addressID = a.addressID', 'left');
        $this->db->where('r.userID', $userID);
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
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

    function getResCate($resID) {
        $this->db->select('*');
        $this->db->from('restaurantcategories rc');
        $this->db->join('categoriesofrestaurant cr', 'rc.categoryOfResID = cr.categoryOfResID', 'left');
        $this->db->where('restaurantID', $resID);
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }

    function getRestaurantByUser($userID) {
        $sql = " SELECT restaurantID FROM restaurants r, users u " .
                " WHERE u.userID = r.userID AND u.userID = " . $userID . " ; ";
        $query = $this->db->query($sql);
        return $query->result();
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

        $this->db->insert('restaurants', $data2);
        $restID = $this->getRestaurantByUser($userID);
        $defaultRate = array(
            'rateValue' => 0,
            'userID' => $userID,
            'restaurantID' => $restID[0] -> restaurantID
        );

        return $this->db->insert('rate', $defaultRate);
    }

    function insertResCate($resID, $bundleCate) {
        // $checked = implode(',',$bundleCate);
        // Delete all old checked
        $this->db->where("restaurantID", $resID);
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

    // return all restaurants information
    public function getInterestingRestaurants() {
        $sql = " SELECT r.restaurantID, img.imageID, addressImage, campaign, nameRe, AVG(rateValue) AS average, discount, address " .
                " FROM restaurants r, images img, address addr, rate, users u " .
                " WHERE r.addressID = addr.addressID AND rate.restaurantID = r.restaurantID AND r.userID = u.userID AND u.userID = img.userID AND typeImage = 1 " .
                " GROUP BY r.restaurantID " .
                " UNION " .
                " SELECT r.restaurantID, img.imageID, addressImage, campaign, nameRe, 0 AS average, discount, address " .
                " FROM restaurants r, images img, address addr, rate, users u " .
                " WHERE r.addressID = addr.addressID AND r.userID = u.userID AND u.userID = img.userID AND typeImage = 1 " .
                " AND r.restaurantID NOT IN( SELECT restaurantID FROM rate ) " .
                " GROUP BY r.restaurantID " .
                " UNION " .
                " SELECT r.restaurantID, img.imageID, 'images/restOwner/restImage/default_restaurant.png', campaign, nameRe, AVG(rateValue) AS average, discount, address " .
                " FROM restaurants r, images img, address addr, rate, users u " .
                " WHERE r.addressID = addr.addressID AND rate.restaurantID = r.restaurantID AND r.userID = u.userID AND typeImage = 1" .
                " AND u.userID NOT IN( SELECT userID FROM images ) " .
                " GROUP BY r.restaurantID;
";

        $query = $this->db->query($sql);
        $data = $query->result();
        return $data;
    }

}

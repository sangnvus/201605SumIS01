<?php

class Restaurants_model extends CI_Model {

    // variables to hold selection fields in sql query
    private $restID = 'r.restaurantID';
    private $all = '*';

    function __construct() {
        parent::__construct();
    }

    public function getQuery($id, $value) {
        return ' SELECT ' . $value . ', AVG(rateValue) AS average ' .
                ' FROM restaurants rest, rate r, images im, address addr, Users u, food f ' .
                ' WHERE rest.restaurantID = ' . $id . ' AND rest.restaurantID = r.restaurantID  AND rest.addressID = addr.addressID ' .
                ' AND rest.userID = u.userID AND f.imageID = im.imageID AND f.restaurantID = rest.restaurantID; ';
    }

    // return restaurant's rating
    public function getRestaurantRating($id) {

        $query = $this->db->query($this->getQuery($id, $this->restID));

        if ($query->num_rows() > 0) {
            $data = $query-> row();
            return $data;
        } else {
            return NULL;
        }
    }

    // return all restaurants information
    public function getInterestingRestaurants($sortBy) {
        // retrieve all restaurants
        $restID = $this->db->query(' SELECT restaurantID AS ID FROM restaurants ');
        $data = array();
        foreach ($restID->result() as $id) {

            // get all restaurants
            $query = $this->db->query($this->getQuery($id->ID, $this->all));
            $values = $query->result_array();
            $data = array_merge($data, $values);
        }

        // check if there is information returned from database
        if ($data != null) {
            if (strcasecmp($sortBy, 'discount') == 0) {
                usort($data, array(__CLASS__, 'sortByDiscount'));
                return $data;
            } else {
                usort($data, array(__CLASS__, 'sortByRating'));
                return $data;
            }
        } else {
            return false;
        }
    }

    // sort by averate rating function in descending order
    function sortByRating($a, $b) {
        return $b['average'] - $a['average'];
    }

    // sort by discount function in descending order
    function sortByDiscount($a, $b) {
        return $b['discount'] - $a['discount'];
    }

}

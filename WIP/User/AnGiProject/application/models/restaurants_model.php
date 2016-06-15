<?php

class Restaurants_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // return amount of each rate based on users' vote
    public function getInterestingRestaurants($sortBy) {

        // retrieve all restaurants
        $restID = $this->db->query(' SELECT restaurantID AS ID FROM restaurants ');

        $data = array();
        foreach ($restID->result() as $id) {

            // get all restaurants
            $query = $this->db->query(' SELECT  *, r.restaurantID AS ID, AVG(rate) AS average ' .
                    ' FROM restaurants rest, rate r, images im, address addr, Users u, food f' .
                    ' WHERE rest.restaurantID = ' . $id->ID . ' AND rest.restaurantID = r.restaurantID AND rest.addressID = addr.addressID AND' .
                    ' rest.userID = u.userID AND f.imageID = im.imageID AND f.restaurantID = rest.restaurantID AND f.restaurantID = rest.restaurantID ' .
                    ' LIMIT 4; ');
            $values = $query->result_array();
            $data = array_merge($data, $values);
        }

        if ($sortBy === 'promotion') {
            return $data;
        } else {
            usort($data, array(__CLASS__, 'sortByKeyCallback'));
            return $data;
        }
    }

    // sort by averate rating function in descending order
    function sortByKeyCallback($a, $b) {
        return $b['average'] - $a['average'];
    }

}

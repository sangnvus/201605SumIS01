<?php

class Food_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getFood($restID) {
        $sql = "SELECT * FROM food f, images img " .
                " WHERE f.imageID = img.imageID AND restaurantID = " . $restID . ";";
        $query = $this -> db -> query($sql);
        if ($query->num_rows() > 0) {
            $data = $query->result();
            return $data;
        } else {
            return null;
        }
    }

}

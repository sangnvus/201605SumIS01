<?php

class Search_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function searchCategory($keyword) {
        $term = '%' . htmlentities($keyword) . '%';
        $sql = " SELECT * FROM restaurantcategories cate, categoriesofrestaurant cateOf " .
                " WHERE nameCOR LIKE " . $this->db->escape($term) . " AND cate.categoryOfResID = cateOf.categoryOfResID; ";

        $query = $this->db->query($sql, $term);
        if ($query->num_rows() > 0) {
            $data = $query->result();
            return $data;
        } else {
            return false;
        }
    }

    function searchRestFood($keyword) {
        $term = '%' . htmlentities($keyword) . '%';
        $sql = " SELECT restaurantID, nameRe, food, campaign, discount, addressID " . 
                " FROM restaurants WHERE nameRe LIKE " . $this->db->escape($term) . " OR food LIKE " . $this->db->escape($term) . "; ";

        $query = $this->db->query($sql, $term, $term);
        if ($query->num_rows() > 0) {
            $data = $query->result();
            return $data;
        } else {
            return false;
        }
    }

}

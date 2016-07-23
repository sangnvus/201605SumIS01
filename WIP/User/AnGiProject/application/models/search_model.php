<?php

class Search_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getRestaurantResult($keyword) {
        $term = '%' . htmlentities($keyword) . '%';
        $sql = " SELECT r.restaurantID, nameRe, food, nameCOR, " .
                " IF (nameRe LIKE '%?%', 1, 0) AS One, " .
                " IF (nameCOR LIKE '%?%', 1, 0) AS Two, " .
                " IF (food LIKE '%?%', 1, 0) AS Three " .
                " FROM restaurants r, restaurantcategories cate, categoriesofrestaurant cateOf " .
                " WHERE cate.restaurantID = r.restaurantID AND cate.categoryOfResID = cateOf.categoryOfResID " .
                " ORDER BY One, Two, Three; ";

        $query = $this->db->query($sql, array($term,$term, $term));
        if ($query->num_rows() > 0) {
            $data = $query->result();
            return $data;
        } else {
            return NULL;
        }
    }

}

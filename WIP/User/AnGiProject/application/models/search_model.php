<?php

class Search_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getSearchRestaurant($keyword) {
        $term = '%' . htmlentities($keyword) . '%';
        $sql = "SELECT  r.restaurantID, addressImage, nameFo, nameRe, nameCOR, nameImage, AVG(rateValue) AS average, discount, address, nameCOR " .
                " FROM food f, restaurants r, restaurantcategories cate, categoriesofrestaurant cateOf, " .
                " images img, address addr, rate " .
                " WHERE (nameFo LIKE " . $this->db->escape($term) . " OR nameCOR LIKE " . $this->db->escape($term) . " OR nameRe LIKE " . $this->db->escape($term) . ") " .
                " AND f.restaurantID = r.restaurantID AND cate.restaurantID = r.restaurantID AND cate.categoryOfResID = cateOf.categoryOfResID " .
                " AND f.imageID = img.imageID AND r.addressID = addr.addressID AND rate.restaurantID = r.restaurantID " .
                " GROUP BY restaurantID; ";

        $query = $this->db->query($sql, $term, $term);
        if ($query->num_rows() > 0) {
            $data = $query->result();
            return $data;
        } else {
            return NULL;
        }
    }

}

<?php

class Image_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function insertImage($data) {
        return $this->db->insert('images', $data);
    }

    function insertFoodImage($data) {
        return $this->db->insert('food', $data);
    }

    function insertRestImage($data) {
        // 1. insert image into image's table
        // 2. insert imageID into food table with specified restaurant id
        $this->insertImage($data);
        $this->insertFoodImage($data);
    }

    function getRestImage($restID) {
        $sql = " SELECT * FROM images img, food f " .
                " WHERE img.imageID = f.imageID AND f.restaurantID = " . $restID . "; ";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $data = $query->result();
            return $data;
        } else {
            return NULL;
        }
    }

    // update restaurant 0 avartar, 1 restaurant, 2 banner
//    function updateAvatar($userID, $typeImage) {
//        $sql = "UPDATE images SET userID = ? WHERE imageID = (SELECT imageID FROM images WHERE userID = ? AND typeImage = ?);";
//        $this -> db -> query($sql, array($userID, $userID, $typeImage));
//    }

    // -------------------- banner -------------------------
    // 0 avartar, 1 restaurant, 2 banner
    function getBanner() {
        $query = $this->db->get_where('images', array('typeImage' => 2));
        return $query->result();
    }

    function insertBannerImage($data, $userID) {
        $this->db->insert('images', $data);
        $this->db->where('userID', $userID);
        $this->db->update("users", $data);
        if ($this->db->affected_rows() == '1') {
            return true;
        } else {
            return false;
        }
    }

    // -------------------- end of banner -------------------------
}

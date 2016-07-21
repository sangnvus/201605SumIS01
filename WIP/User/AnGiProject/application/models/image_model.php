<?php

class Image_model extends CI_Model {

    function __construct() {
        parent::__construct();
        // typeImage: 0 customer avatar, 1 restaurant avatar, 2 banner, 3 food
        // authorityUser: 1 customer, 2 restaurant owner
    }

    function insertImage($data) {
        return $this->db->insert('images', $data);
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

    // -------------------- avatar -------------------------
    // update restaurant 0 avartar, 1 restaurant, 2 banner
    function getAvatar($userType, $userID, $imageType) {
        $sql = " SELECT * FROM images img, users u " .
                " WHERE img.userID = u.userID AND authorityUser = ? AND img.userID = ? AND typeImage = ?;";
        $query = $this->db->query($sql, array($userType, $userID, $imageType));
        return $query->result();
    }

    function insertAvatar($data) {
        $this->db->insert('images', $data);
    }

    function deleteAvatar($userType, $userID, $imageType) {
        $avatar = $this->getAvatar($userType, $userID, $imageType);
        $previousImageID = null;
        foreach ($avatar as $row) {
            $previousImageID = $row->imageID;
        }
        if ($previousImageID == null) {
            return;
        } else {
            $sql = "DELETE FROM images WHERE imageID = " . $previousImageID;
            $this->db->query($sql);
        }
    }

    // -------------------- end of avatar -------------------------
    // -------------------- banner -------------------------
    // user types: 1 customer, 2 restaurant owner
    // image types: 0 customer avatar, 1 restaurant avatar, 2 banner
    function getBanner($userID) {
        $sql = " SELECT * FROM images img, users u " .
                " WHERE img.userID = u.userID AND authorityUser = 2 AND img.userID = ? AND typeImage = 2;";
        $query = $this->db->query($sql, array($userID));
        return $query->result();
    }

    function insertBannerImage($data) {
        $this->db->insert('images', $data);
    }

    function deleteBanner($imgID) {
        $sql = " DELETE FROM images WHERE imageID = ?; ";
        $this->db->query($sql, array($imgID));
    }

    // -------------------- end of banner -------------------------
}

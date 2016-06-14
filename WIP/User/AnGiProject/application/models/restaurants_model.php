<?php

class Restaurants_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // return amount of each rate based on users' vote
    public function getTopRatingRestaurant() {

        // get all restaurants
        $restID = $this->db->query(' SELECT  r.restaurantID AS ID, nameRe, addressImage, address ' .
                ' FROM restaurants rest, rate r, images im, address addr, Users u, food f' .
                ' WHERE rest.restaurantID = r.restaurantID AND rest.addressID = addr.addressID AND' .
                ' rest.userID = u.userID AND f.imageID = im.imageID AND f.restaurantID = rest.restaurantID; ');

        $values = array();
        foreach ($restID->result() as $id) {

            // individual rate for each table
            ${'query1Rest' . $id->ID} = $this->db->query(' SELECT COUNT(rate) AS Total FROM rate r, restaurants rest, Users u ' .
                    ' WHERE rate = 1 AND r.restaurantID = ' . $id->ID . ' AND '
                    . ' r.restaurantID = rest.restaurantID AND r.userID = u.userID; ');

            ${'query2Rest' . $id->ID} = $this->db->query(' SELECT COUNT(rate) AS Total FROM rate r, restaurants rest, Users u ' .
                    ' WHERE rate = 2 AND r.restaurantID = ' . $id->ID . ' AND '
                    . ' r.restaurantID = rest.restaurantID AND r.userID = u.userID; ');

            ${'query3Rest' . $id->ID} = $this->db->query(' SELECT COUNT(rate) AS Total FROM rate r, restaurants rest, Users u ' .
                    ' WHERE rate = 3 AND r.restaurantID = ' . $id->ID . ' AND '
                    . ' r.restaurantID = rest.restaurantID AND r.userID = u.userID; ');

            ${'query4Rest' . $id->ID} = $this->db->query(' SELECT COUNT(rate) AS Total FROM rate r, restaurants rest, Users u ' .
                    ' WHERE rate = 4 AND r.restaurantID = ' . $id->ID . ' AND '
                    . ' r.restaurantID = rest.restaurantID AND r.userID = u.userID; ');

            ${'query5Rest' . $id->ID} = $this->db->query(' SELECT COUNT(rate) AS Total FROM rate r, restaurants rest, Users u ' .
                    ' WHERE rate = 5 AND r.restaurantID = ' . $id->ID . ' AND'
                    . ' r.restaurantID = rest.restaurantID AND r.userID = u.userID; ');

            // retrieve all restaurants rates 
            ${'start1Rest' . $id->ID} = ${'query1Rest' . $id->ID}->row()->Total;
            ${'start2Rest' . $id->ID} = ${'query2Rest' . $id->ID}->row()->Total;
            ${'start3Rest' . $id->ID} = ${'query3Rest' . $id->ID}->row()->Total;
            ${'start4Rest' . $id->ID} = ${'query4Rest' . $id->ID}->row()->Total;
            ${'start5Rest' . $id->ID} = ${'query5Rest' . $id->ID}->row()->Total;

            // total votes for each restaurant
            ${'totalQueryRest' . $id->ID} = $this->db->query(' SELECT COUNT(*) as votes FROM rate r, restaurants rest ' .
                    ' WHERE r.restaurantID = ' . $id->ID . ' AND r.restaurantID = rest.restaurantID; ');

            $totalVotes = ${'totalQueryRest' . $id->ID}->row()->votes;

            // average rate of of each restaurant
            if ($totalVotes != 0) {
                ${'average' . $id->ID} = (( ${'start1Rest' . $id->ID} + ( ${'start2Rest' . $id->ID} * 2) + ( ${'start3Rest' . $id->ID} * 3) +
                        ( ${'start4Rest' . $id->ID} * 4) + ( ${'start5Rest' . $id->ID} * 5)) / $totalVotes);
            } else {
                ${'average' . $id->ID} = 0;
            }

            // retrieve all restaurants information and add average rating  
            $data = array($id->ID, $id->nameRe, $id->address, $id-> addressImage, ${'average' . $id->ID});
            array_push($values, $data);
            
            // --------------- output after sort DSC ----------------
            // Array ( 
            //        [0] => Array ( 
            //                      [0] => 2 [1] => Buffet Restaurant [2] => lane 34 [3] => resource/image/dish1.jpg [4] => 5 ) 
            //        [1] => Array ( 
            //                      [0] => 1 [1] => Mojo BBQ [2] => lane 34 [3] => resource/image/dish1.jpg [4] => 4 ) 
            // 			)
            // ------------------------------------------------------
        }

//      sort restaurants in descending orde r by averate rating
        usort($values, array(__CLASS__, 'sortByKeyCallback'));

        return $values;
    }

    // 4 is the index of average rating in $data
    function sortByKeyCallback($a, $b) {
        return $b[4] - $a[4];
    }
    
    
    public function getTopPromotionRestaurant(){
        $query = $this -> db -> query(' SELECT * FROM images WHERE imageID = 2; ');
        $data = $query -> result();
        return $data;
    }

}

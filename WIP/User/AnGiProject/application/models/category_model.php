<?php
class Category_model extends CI_Model
{
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	

	function getRestaurant($local)
    {
    	if ($local == 0){
    		$this->db->select('*');
		$this->db->from('restaurants r'); 
        $this->db->join('address a', 'r.addressID = a.addressID');
        $this->db->join('district d', 'd.districtID = a.districtID');
        $query = $this->db->get(); 
        $data=$query->result_array();
   		return  $data;
    	}
    	else{
			$this->db->select('*');
			$this->db->from('restaurants r'); 
		    $this->db->join('address a', 'r.addressID = a.addressID');
		    $this->db->join('district d', 'd.districtID = a.districtID');
		    $this->db->where('d.districtID',$local); 
		    $query = $this->db->get(); 
		    $data=$query->result_array();
			return  $data;
		}
	}

    function getRate($restaurantID){
        $this->db->select_avg('rateValue','overall');
        $this->db->from('rate');
        $this->db->where('restaurantID', $restaurantID);
        $data = $this->db->get()->row();
        return  $data;
    }
	function getDistrict($table)
    {
		$query = $this->db->get_where('district', array('provinceID' => 01));
        $data=$query->result_array();
   		return  $data;
	}


	function getAddress($ID)
    {
    	$this->db->select('*');
        $this->db->from('address a'); 
        $this->db->join('province p', 'p.provinceID = a.provinceID');
        $this->db->join('ward w', 'w.wardid = a.wardID');
        $this->db->join('district d', 'd.districtID = a.districtID');
        $this->db->where('a.addressID',$ID); 
        $query = $this->db->get(); 
        $data=$query->result_array();
   		return  $data;
	}

}
<?php
class Category_model extends CI_Model
{
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function countAll($type,$ID){

        $this->db->select('count(*) as count' );
        $this->db->from('restaurants r'); 
        $this->db->join('address a', 'r.addressID = a.addressID');
        
        if($type == 1){
            $this->db->join('restaurantcategories rc', 'rc.restaurantID = r.restaurantID');
            $this->db->where('rc.categoryOfResID',$ID); 
        }
        elseif ($type == 2) {
            $this->db->join('district d', 'd.districtID = a.districtID');
            $this->db->where('d.districtID',$ID);  
        }
        
        $data = $this->db->get()->row();
        return  $data->count;
        
    }
	
    function getRes($type,$ID,$count,$page){
        //sua so items/trang
        // max - min
        $this->db->limit($page*1, ($page-1)*1);    
        $this->db->select('*');
        $this->db->from('restaurants r'); 
        $this->db->join('address a', 'r.addressID = a.addressID');

        if($type == 1){
            $this->db->join('restaurantcategories rc', 'rc.restaurantID = r.restaurantID');
            $this->db->where('rc.categoryOfResID',$ID); 
        }
        if ($type == 2) {
            $this->db->join('district d', 'd.districtID = a.districtID');
            $this->db->where('d.districtID',$ID);  
        }

        $query = $this->db->get(); 
        $data=$query->result_array();
        return  $data;
    }

    // function getResByLocal($ID)
    // {
    //     $this->db->select('*');
    //     $this->db->from('restaurants r'); 
    //     $this->db->join('address a', 'r.addressID = a.addressID');
    //     $this->db->join('district d', 'd.districtID = a.districtID');
    //     $this->db->where('d.districtID',$ID); 
    //     $query = $this->db->get(); 
    //     $data=$query->result_array();
    //     return  $data;
    // }

    // function getResByCate($ID)
    // {
    //     if ($ID == 0){
    //     $this->db->select('*');
    //     $this->db->from('restaurants r'); 
    //     $this->db->join('address a', 'r.addressID = a.addressID');
    //     $query = $this->db->get(); 
    //     $data=$query->result_array();
    //     return  $data;
    //     }
    //     else{
    //         $this->db->select('*');
    //         $this->db->from('restaurants r'); 
    //         $this->db->join('address a', 'r.addressID = a.addressID');
    //         $this->db->join('restaurantcategories rc', 'rc.restaurantID = r.restaurantID');
    //         $this->db->where('rc.categoryOfResID',$ID); 
    //         $query = $this->db->get(); 
    //         $data=$query->result_array();
    //         return  $data;
    //     }
    // }



    function getRate($restaurantID){
        $this->db->select_avg('rateValue','overall');
        $this->db->from('rate');
        $this->db->where('restaurantID', $restaurantID);
        $data = $this->db->get()->row();
        return  $data->overall;
    }

	function getDistrict()
    {
		$query = $this->db->get_where('district', array('provinceID' => 01));
        $data=$query->result_array();
   		return  $data;
	}

    function getCategories()
    {
        $query = $this->db->get('categoriesofrestaurant');
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
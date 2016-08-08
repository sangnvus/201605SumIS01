<?php               
class AddressModel extends CI_Model
{
    private $table = 'address';  
    function __construct()
    {
        parent::__construct();
    }
    function ListAll()
    {                                          
        $query = $this->db->get($this->table);
        //print_r($query->result());exit();
        return $query->result();
    }
    function Count_All()
    {
        return $this->db->count_all($this->table);
    }
    
}

?>
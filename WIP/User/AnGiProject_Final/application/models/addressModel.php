<?php               
class AddressModel extends CI_Model
{
    private $table = 'address';  
    private $province = 'province';  
    private $district = 'district';  
    private $ward = 'ward';  
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
    
    function FindDistrictByProvinceId($provinceId)
    {   
        $query = $this->db->get_where($this->district, array('provinceID'=>$provinceId));
        return $query->result();
    }
    
    function FindWardByProvinceId($districtId)
    {   
        $query = $this->db->get_where($this->ward, array('districtID'=>$districtId));
        return $query->result();
    }
    
    function ListAllProvince()
    {                                         
        $query = $this->db->get($this->province);  
        return $query->result();
    }
    
    function GetById($id)
    {
        $query = $this->db->get_where($this->table, array('addressID'=>$id), 1);
        $record = $query->row();
        return $record;
    }
    
}

?>
<?php               
class ImageModel extends CI_Model
{
    private $table = 'images';  
    function __construct()
    {
        parent::__construct();
    }
    function ListAll()
    {                                          
        $query = $this->db->get($this->table);     
        return $query->result();
    }
    function Count_All()
    {
        return $this->db->count_all($this->table);
    }
    
    function Admin_GetById($id)
    {
        $query = $this->db->get_where($this->table, array('imageID'=>$id), 1);
        $record = $query->row();
        return $record;
    }
    
}

?>
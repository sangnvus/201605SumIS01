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
    function GetById($id)
    {
        $query = $this->db->get_where($this->table, array('imageID'=>$id), 1);
        $record = $query->row();
        return $record;
    }
    
    function Create($info)
    {                                 
        //Insert du lieu vao bang user
        $this->db->insert($this->table, $info);
        $id = $this->db->insert_id();
        if ($id > 0)                   
        {   
            return $id;
        }
        else
        {                         
            return 0;
        }        
    }
    
    function Update($id, $info)  
    {            
        $id = $this->db->update($this->table, $info, array('imageID'=>$id));
        if ($id > 0)                   
        {   
            return $id;
        }
        else
        {                         
            return 0;
        } 
    }
}

?>
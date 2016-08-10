<?php               
class RestaurantBannerModel extends CI_Model
{
    private $table = 'restaurantimage';  
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
     
    function GetById($id)
    {
        $query = $this->db->get_where($this->table, array('restaurantImageId'=>$id), 1);
        $record = $query->row();
        return $record;
    }
        
    function FindImagePaged($offset=0, $limit=20, &$count, $resId=0){
        $from = 'from '.$this->table;
        $where = ' where 1 = 1 ';
        if ($resId > 0){
            $where .= ' and '.$this->table.'.restaurantId = '.$resId.' ';                               
        }
        
        $select = 'select 1 ';
        $query = $this->db->query($select.$from.$where);
        $count = $query->num_rows();
        
        $select = 'select '.$this->table.'.* ';       
        $paging = ' limit '.$limit.' offset '.$offset;
        
        $query = $this->db->query($select.$from.$where.$paging);
        return $query->result();
    } 
    
    
    function Create($info)
    {   
        //Bat dau trans
        $this->db->trans_begin();        
        //Insert du lieu vao bang user
        $this->db->insert($this->table, $info);
        $restaurantImageId = $this->db->insert_id();                  
        if ($info['imageMain'] == 1 || $info['imageMain'] == 'on' || $info['imageMain'] == true){
            //thực hiện đổi các ảnh khác sang ảnh thường
            $this->db->update($this->table, array('imageMain'=> false), 'restaurantImageId != '.$restaurantImageId);
        }                                    
        if ($this->db->trans_status() == false)
        {
            $this->db->trans_rollback();
            return false;
        }
        else
        {
            $this->db->trans_commit();
            return true;
        }        
    }
    
    function Update($id, $info)
    {                    
        $this->db->trans_begin();  
        $this->db->update($this->table, $info, array('restaurantImageId'=>$id));         
        if ($this->db->trans_status() == false)
        {
            $this->db->trans_rollback();
            return false;
        }
        else
        {
            $this->db->trans_commit();
            return true;
        }
    }
    //Xoa thong tin user trong bang users
    function Delete($id)
    {
        //Bat dau trans
        $this->db->trans_begin(); 
        $this->db->delete($this->table, array('restaurantImageId'=>$id)); 
        if ($this->db->trans_status() == false)
        {
            $this->db->trans_rollback();
            return false;
        }
        else
        {
            $this->db->trans_commit();
            return true;
        }
    }
}

?>
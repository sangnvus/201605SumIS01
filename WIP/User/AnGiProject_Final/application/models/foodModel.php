<?php               
class FoodModel extends CI_Model
{
    private $table = 'food'; 
    private $restaurants = 'restaurants';     
    private $images = 'images';     
    function __construct()   
    {
        parent::__construct();
    }
    function ListAll()
    {                                          
        $query = $this->db->get($this->table);
        return $query->result();
    }
    
    function Count_By($resId=0)
    {   
        $sql = 'select foodID from '.$this->table.' where restaurantID = '.$resId;
        $query = $this->db->query($sql);
        return $query->num_rows();
    }
    
    public function Admin_FindBy($resId, $offset=0, $limit=20){
        $sql = 'select '.$this->table.'.*, ';                  
        $sql.= $this->restaurants.'.nameRe as restaurantName, ';
        $sql.= $this->images.'.addressImage as imageIDSrc ';
        $sql.= 'from '.$this->table;                                                                      
        $sql.= ' left join '.$this->restaurants.' on '.$this->table.'.restaurantID = '.$this->restaurants.'.restaurantID '; 
        $sql.= ' left join '.$this->images.' on '.$this->table.'.imageID = '.$this->images.'.imageID '; 
        $sql.= ' where '.$this->table.'.restaurantID = '.$resId;
        $sql.= ' limit '.$limit.' offset '.$offset;
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    public function Admin_GetDetail($id){
        $sql = 'select '.$this->table.'.*, ';                  
        $sql.= $this->restaurants.'.nameRe as restaurantName ';
        $sql.= 'from '.$this->table;                                                                      
        $sql.= ' left join '.$this->restaurants.' on '.$this->table.'.restaurantID = '.$this->restaurants.'.restaurantID'; 
        $sql.= ' where '.$this->table.'.foodID = '.$id; 
        $sql.= ' limit 1';
        $query = $this->db->query($sql);  
        if ($query->num_rows() > 0)
            return $query->row();
        return null;
    }
    
    function Count_All()
    {
        return $this->db->count_all($this->table);
    }
    
    function Admin_GetById($id)
    {
        $query = $this->db->get_where($this->table, array('foodID'=>$id), 1);
        $record = $query->row();
        return $record;
    }
    
    function Create($info, $image)
    {   
        //Bat dau trans
        $this->db->trans_begin();        
        //Insert du lieu vao bang user
        $this->db->insert($this->images, $image);
        //Tráº£ ra user_id vua insert
        $imageID = $this->db->insert_id();
        if($imageID > 0)
        {
            //Neu insert thanh cong vao bang user             
            $info['imageID'] =  $imageID;
            $this->db->insert($this->table, $info);
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
    
    function Update($id, $info, $image=null)
    {                    
        $this->db->trans_begin();
        if ($image != null){           
            $this->db->insert($this->images, $image);
            $imageID = $this->db->insert_id();                 
            $info['imageID'] =  $imageID;
        }
        $this->db->update($this->table, $info, array('foodID'=>$id));         
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
        $this->db->delete($this->table, array('foodID'=>$id)); 
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
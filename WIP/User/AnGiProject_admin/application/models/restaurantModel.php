<?php               
class RestaurantModel extends CI_Model
{
    private $table = 'restaurants';  
    private $users = 'users';
    private $memberships = 'memberships';
    private $address = 'address';
    private $restaurantcategories = 'restaurantcategories';
    private $categoriesofrestaurant = 'categoriesofrestaurant';
    private $food = 'food';
    function __construct()
    {
        parent::__construct();
    }
    function ListAll()
    {                                          
        $query = $this->db->get($this->table);
        return $query->result();
    }
    
    public function Admin_FindBy($offset=0, $limit=20, & $count=0){
        $from = 'from '.$this->table;
        $from.= ' left join '.$this->memberships.' on '.$this->table.'.userID = '.$this->memberships.'.userID ';   
        $from.= ' left join '.$this->address.' on '.$this->table.'.addressID = '.$this->address.'.addressID ';   
        $from.= ' left join '.$this->restaurantcategories.' on '.$this->table.'.restaurantID = '.$this->restaurantcategories.'.restaurantID ';   
        $from.= ' left join '.$this->categoriesofrestaurant.' on '.$this->restaurantcategories.'.categoryOfResID = '.$this->categoriesofrestaurant.'.categoryOfResID ';   
        
        $select = 'select 1 ';
        //count
        $query = $this->db->query($select.$from);
        $count = $query->num_rows();
        
        $select = 'select '.$this->table.'.*, ';
        $select.= $this->memberships.'.memName as userName, '; 
        $select.= $this->address.'.address, '; 
        $select.= $this->categoriesofrestaurant.'.nameCOR as categoryName '; 
        $where = ' order by '.$this->table.'.nameRe ';
        $where.= ' limit '.$limit.' offset '.$offset;
        
        $query = $this->db->query($select.$from.$where);
        return $query->result();
    }
    
    function Count_All()
    {
        return $this->db->count_all($this->table);
    }
    
    function Admin_GetById($id)
    {
        $query = $this->db->get_where($this->table, array('restaurantID'=>$id), 1);
        $record = $query->row();
        return $record;
    }
    
    function Admin_GetResCateByResId($id)
    {
        $query = $this->db->get_where($this->restaurantcategories, array('restaurantID'=>$id), 1);
        $record = $query->row();
        return $record;
    }
    
    function Create($info, $cateAdd)
    {   
        //Bat dau trans
        $this->db->trans_begin();        
        //Insert du lieu vao bang user
        $this->db->insert($this->table, $info);
        //Tráº£ ra user_id vua insert
        $restaurantID = $this->db->insert_id();
        if($restaurantID > 0)
        {
            //Neu insert thanh cong vao bang user             
            $cateAdd['restaurantID'] =  $restaurantID;
            $this->db->insert($this->restaurantcategories, $cateAdd);
        }                                         
        if ($this->db->trans_status() == false)
        {
            $this->db->trans_rollback();
            return 0;
        }
        else
        {
            $this->db->trans_commit();
            return $restaurantID;
        }        
    }
    
    function Update($id, $info, $cateEdit)
    {                    
        $this->db->trans_begin();
        $this->db->update($this->table, $info, array('restaurantID'=>$id)); 
        $this->db->update($this->restaurantcategories, $cateEdit, array('restaurantID'=>$id));        
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
        $this->db->delete($this->table, array('restaurantID'=>$id)); 
        $this->db->delete($this->restaurantcategories, array('restaurantID'=>$id)); 
        $this->db->delete($this->restaurantcategories, array('restaurantID'=>$id)); 
        $this->db->delete($this->food, array('restaurantID'=>$id)); 
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
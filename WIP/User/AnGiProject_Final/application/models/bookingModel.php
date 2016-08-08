<?php               
class BookingModel extends CI_Model
{
    private $table = 'booking';  
    private $restaurants = 'restaurants';  
    private $memberships = 'memberships';
    function __construct()
    {
        parent::__construct();
    }
    function ListAll()
    {                                          
        $query = $this->db->get($this->table);
        return $query->result();
    }
    
    public function Admin_FindBy($restaurantID = null, $userID = null, $offset=0, $limit=20, &$count=0){    
        $from = 'from '.$this->table;
        $from.= ' left join '.$this->memberships.' on '.$this->table.'.userID = '.$this->memberships.'.userID';
        $from.= ' left join '.$this->restaurants.' on '.$this->restaurants.'.restaurantID = '.$this->table.'.restaurantID'; 
        
        $where = ' where 1 = 1 ';
        if ($restaurantID != null)
            $where .= ' and '.$this->table.'.restaurantID = '.$restaurantID;
        if ($userID != null)
            $where .= ' and '.$this->table.'.userID = '.$userID;
             
        $select = 'select distinct '.$this->table.'.bookingID ';

        //count
        $query = $this->db->query($select.$from.$where);
        $count = $query->num_rows();
        
        $select = 'select '.$this->table.'.*, ';
        $select.= $this->memberships.'.memName as userName, ';
        $select.= $this->restaurants.'.nameRe as restaurantName ';  
        $paging = ' limit '.$limit.' offset '.$offset;
        $order = ' order by dateBooking desc ';
          
        $query = $this->db->query($select.$from.$where.$order.$paging);
        return $query->result();
    }
    
    public function Admin_GetDetail($id){
        $sql = 'select '.$this->table.'.*, ';
        $sql.= $this->memberships.'.memName as userName, ';
        $sql.= $this->restaurants.'.nameRe as restaurantName ';
        $sql.= 'from '.$this->table;
        $sql.= ' left join '.$this->memberships.' on '.$this->table.'.userID = '.$this->memberships.'.userID';
        $sql.= ' left join '.$this->restaurants.' on '.$this->table.'.restaurantID = '.$this->restaurants.'.restaurantID'; 
        $sql.= ' where '.$this->table.'.bookingID = '.$id;  
        $sql.= ' limit 1';
        $query = $this->db->query($sql);  
        if ($query->num_rows() > 0)
            return $query->row();
        return null;
    }
    
    public function CheckExisted($data){
        $query = $this->db->get_where($this->table, $data, 1);
        if ($query->num_rows() > 0)
            return true;
        return false;
    }
    
    function Count_All()
    {
        return $this->db->count_all($this->table);
    }
    
    function Admin_GetById($id)
    {
        $query = $this->db->get_where($this->table, array('bookingID'=>$id), 1);
        $record = $query->row();
        return $record;
    }
    
    function Create($info)
    {   
        //Bat dau trans
        $this->db->trans_begin();        
        //Insert du lieu vao bang user
        $this->db->insert($this->table, $info);
        
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
        $this->db->update($this->table, $info, array('bookingID'=>$id));         
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
        $this->db->delete($this->table, array('bookingID'=>$id)); 
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
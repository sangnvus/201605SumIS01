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
    
    public function Admin_FindBy($offset=0, $limit=20){
        $sql = 'select '.$this->table.'.*, ';
        $sql.= $this->memberships.'.memName as userName, ';
        $sql.= $this->restaurants.'.nameRe as restaurantName ';
        $sql.= 'from '.$this->table;
        $sql.= ' left join '.$this->memberships.' on '.$this->table.'.userID = '.$this->memberships.'.userID';
        $sql.= ' left join '.$this->restaurants.' on '.$this->restaurants.'.restaurantID = '.$this->restaurants.'.restaurantID'; 
        $sql.= ' order by dateBooking desc ';
        $sql.= ' limit '.$limit.' offset '.$offset;
        $query = $this->db->query($sql);
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
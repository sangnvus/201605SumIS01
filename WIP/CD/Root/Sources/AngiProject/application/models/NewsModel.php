<?php               
class NewsModel extends CI_Model
{
    private $table = 'news';  
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
    
    public function Admin_FindBy($offset=0, $limit=20, & $count=0){
        $from = 'from '.$this->table;   
        
        $select = 'select 1 ';
        //count
        $query = $this->db->query($select.$from);
        $count = $query->num_rows();
        
        $select = 'select '.$this->table.'.* '; 
        $where = ' order by '.$this->table.'.newsID desc ';
        $where.= ' limit '.$limit.' offset '.$offset;
        
        $query = $this->db->query($select.$from.$where);
        return $query->result();
    }
     
    function GetById($id)
    {
        $query = $this->db->get_where($this->table, array('newsID'=>$id), 1);
        $record = $query->row();
        return $record;
    }
    
    function FindNewsForHome($offset=0, $limit=20, &$count){
        $from = 'from '.$this->table;
        $where = ' where '.$this->table.'.statusNews = 1 ';
        
        $select = 'select 1 ';
        $query = $this->db->query($select.$from.$where);
        $count = $query->num_rows();
        
        $select = 'select '.$this->table.'.* ';     
        $order = ' order by '.$this->table.'.newsID desc ';
        $paging = ' limit '.$limit.' offset '.$offset;
        
        $query = $this->db->query($select.$from.$where.$order.$paging);
        return $query->result();
    } 
    
    function FindNewsPaged($offset=0, $limit=20, &$count, $cat=0){
        $from = 'from '.$this->table;
        $where = ' where '.$this->table.'.statusNews = 1 ';
        if ($cat > 0){
            $where .= ' and ('.$this->table.'.typeNews = '.$cat.') ';                               
        }
        
        $select = 'select 1 ';
        $query = $this->db->query($select.$from.$where);
        $count = $query->num_rows();
        
        $select = 'select '.$this->table.'.* ';     
        $order = ' order by '.$this->table.'.newsID desc ';
        $paging = ' limit '.$limit.' offset '.$offset;
        
        $query = $this->db->query($select.$from.$where.$order.$paging);
        return $query->result();
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
        $this->db->update($this->table, $info, array('newsID'=>$id));         
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
        $this->db->delete($this->table, array('newsID'=>$id)); 
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
    
    public function Report_Statistics(){
        $select = 'select ';
        $select .= '(select count(1) from '.$this->table.' where  statusNews = 0) as numberNotActive, ';
        $select .= '(select count(1) from '.$this->table.' where  statusNews = 1) as numberActived, ';
        $select .= '(select count(1) from '.$this->table.') as total';
        $query = $this->db->query($select);
        if ($query->num_rows() > 0)
            return $query->row();
        return null;
    }
}

?>
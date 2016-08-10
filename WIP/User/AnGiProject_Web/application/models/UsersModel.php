<?php               
class UsersModel extends CI_Model
{
    private $table = 'users';
    private $memberships = 'memberships';
    private $address = 'address';
    private $images = 'images';
    function __construct()
    {
        parent::__construct();
    }
    function ListAll()
    {
        return $this->db->get($this->table);
    }
    function Count_All()
    {
        return $this->db->count_all($this->table);
    }
    function Count_By($level=0)
    {   
        $sql = 'select userID from '.$this->table.' where userLevel = \''.$level.'\'';
        $query = $this->db->query($sql);
        return $query->num_rows();
    }
    function GetUserById($id)
    {
        $query = $this->db->get_where($this->table, array('userID'=>$id), 1);
        $record = $query->row();
        return $record;
    }
    
    function Create($info)
    {
        //cat mang 2 chieu thang 2 mang mot chieu
        $array_user = $info['user'];
        $array_mem = $info['mem'];
        $array_add = $info['add'];
        //Bat dau trans
        $this->db->trans_begin();   
        $this->db->insert($this->address, $array_add); 
        $addressId = $this->db->insert_id();
        //Insert du lieu vao bang user
        $this->db->insert($this->table, $array_user);
        //Tráº£ ra user_id vua insert
        $user_id = $this->db->insert_id();
        if($user_id > 0)
        {
            //Neu insert thanh cong vao bang user
            //add them user_id vao mang chua thong tin chi tiet
            $array_mem['userID'] = $user_id;     
            $array_mem['addressID'] = $addressId;
            $this->db->insert($this->memberships, $array_mem);
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
        $array_user = $info['user'];
        $array_mem = isset($info['mem']) ? $info['mem'] : null;
        $array_add = isset($info['add']) ? $info['add'] : null;
        $this->db->trans_begin();
        
        $this->db->update($this->table, $array_user, array('userID'=>$id));
        if ($array_mem != null)
            $this->db->update($this->memberships, $array_mem, array('userID'=>$id));
        if ($array_add != null)
            $this->db->update($this->address, $array_add, array('addressID'=>$array_mem['addressID']));
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
    
    function UpdateMemberships($memId, $memInfo)
    {                                                         
        $this->db->trans_begin();
        
        $this->db->update($this->memberships, $memInfo, array('memID'=>$memId));
        if ($this->db->trans_status() == false)
        {
            $this->db->trans_rollback();
            return 0;
        }
        else
        {
            $this->db->trans_commit();
            return 1;
        }
    }
    
    //Xoa thong tin user trong bang users
    function Delete($id)
    {
        //Bat dau trans
        $this->db->trans_begin(); 
        $this->db->delete($this->table, array('userID'=>$id));
        //Xoa thong tin trong bang memberships
        $this->db->delete($this->memberships, array('userID'=>$id));
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
    
    //Liet ke chi tiet tat cac user
    function Admin_GetUserById($userid = "")
    {
        $sql = 'select '.$this->table.'.*, ';
        $sql.= $this->memberships.'.addressID,'.$this->memberships.'.imageID,'.$this->memberships.'.memGender,'.$this->memberships.'.memBirthDay, memID, memName, memBirthDay, ';
        $sql.= $this->address.'.address,'.$this->address.'.provinceID,'.$this->address.'.districtID,'.$this->address.'.wardID, ';
        $sql.= $this->images.'.addressImage ';
        $sql.= 'from '.$this->table;
        $sql.= ' left join '.$this->memberships.' on '.$this->table.'.userID = '.$this->memberships.'.userID';
        $sql.= ' left join '.$this->address.' on '.$this->address.'.addressID = '.$this->memberships.'.addressID';
        $sql.= ' left join '.$this->images.' on '.$this->images.'.imageID = '.$this->memberships.'.imageID';
        $sql.= ' where '.$this->table.'.userID = '.$userid.' ';    
        $sql.= ' limit 1';
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0)
            return $query->row();
        return null;
    }
    
    function CheckUserInLogin($named = "", $pwd='', & $flag, & $msg)
    {
        $flag=false;
        $msg='';
        
        $sql = 'SELECT userID FROM '.$this->table.' WHERE userName = \''.$named.'\' OR userMail = \''.$named.'\' limit 1 ';   
        
        $query = $this->db->query($sql);
        if ($query->num_rows() == 0){
            $msg = 'Tên đăng nhập không đúng';
            $flag = false;
            return null;
        }     
        $userID = $query->row()->userID;  
        $sql = 'select '.$this->table.'.*, ';
        $sql.= $this->memberships.'.memName ';
        $sql.= ' from '.$this->table;
        $sql.= ' left join '.$this->memberships.' on '.$this->table.'.userID = '.$this->memberships.'.userID ';
        $sql.= ' where '.$this->table.'.userID = '.$userID.' AND '.$this->table.'.userPass = md5(\''.$pwd.'\') ';      
        $sql.= ' limit 1';
        $query = $this->db->query($sql);
        
        if ($query->num_rows() == 0)  {
            $msg = 'Mật khẩu không đúng';
            $flag = false;
            return null;
        }
        $flag = true; 
        return $query->row();
    }
    
    function GetUserByNamed($named = "")
    {
        $sql = 'select '.$this->table.'.*, ';
        $sql.= $this->memberships.'.addressID,'.$this->memberships.'.imageID,'.$this->memberships.'.memGender,'.$this->memberships.'.memBirthDay, memID, memName, memBirthDay, ';
        $sql.= $this->address.'.address,'.$this->address.'.provinceID,'.$this->address.'.districtID,'.$this->address.'.wardID, ';
        $sql.= $this->images.'.addressImage ';
        $sql.= 'from '.$this->table;
        $sql.= ' left join '.$this->memberships.' on '.$this->table.'.userID = '.$this->memberships.'.userID';
        $sql.= ' left join '.$this->address.' on '.$this->address.'.addressID = '.$this->memberships.'.addressID';
        $sql.= ' left join '.$this->images.' on '.$this->images.'.imageID = '.$this->memberships.'.imageID';
        $sql.= ' where ('.$this->table.'.userName = \''.$named.'\'';
        $sql.= ' or '.$this->table.'.userMail = \''.$named.'\') ';
        $sql.= ' limit 1';
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0)
            return $query->row();
        return null;
    }
    
    //Liet ke chi tiet tat cac user
    function CheckNameOrEmailExisted($named = "", $id = '')
    {
        $sql = 'select 1 ';
        $sql.= 'from '.$this->table;
        $sql.= ' where ('.$this->table.'.userName = \''.$named.'\'';
        $sql.= ' or '.$this->table.'.userMail = \''.$named.'\') ';
        if ($id != ''){
            $sql.= ' and '.$this->table.'.userID != '.$id.' ';    
        }               
        $query = $this->db->query($sql);   
        if ($query->num_rows() != null)
            return true;
        return false;
    }
    
    //Lay ra danh sach user theo offset
    function Admin_GetUser($level=0, $limit = 10, $offset = 0)
    {
        $sql = 'select '.$this->table.'.*, ';
        $sql.= $this->memberships.'.addressID,'.$this->memberships.'.imageID,'.$this->memberships.'.memGender,'.$this->memberships.'.memBirthDay, memName, ';
        $sql.= $this->address.'.address ';
        $sql.= 'from '.$this->table;
        $sql.= ' left join '.$this->memberships.' on '.$this->table.'.userID = '.$this->memberships.'.userID';
        $sql.= ' left join '.$this->address.' on '.$this->address.'.addressID = '.$this->memberships.'.addressID';
        $sql.= ' left join '.$this->images.' on '.$this->images.'.imageID = '.$this->memberships.'.imageID';
        $sql.= ' where '.$this->table.'.userLevel = \''.$level.'\'';
        $sql.= ' limit '.$limit.' offset '.$offset;
        $query = $this->db->query($sql);
        //print_r($query->result());exit();
        return $query->result();
    }
    
    function GetIdByUserName($user_name = "")
    {
        $this->db->select("*");
        $this->db->from("users");
        $this->db->join("memberships", $this->table.".userID = memberships.userID" );
        //Neu userid == "" liet ke tat ca cac user
        if($user_name != "")
        {
            $this->db->where($this->table.".userName", $user_name);
            $this->db->limit(1);
        }        
        return $this->db->get();
    }
}

?>
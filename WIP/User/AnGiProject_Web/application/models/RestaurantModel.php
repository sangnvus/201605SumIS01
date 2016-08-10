<?php               
class RestaurantModel extends CI_Model
{
    private $table = 'restaurants';  
    private $users = 'users';
    private $memberships = 'memberships';
    private $address = 'address';
    private $province = 'province';
    private $district = 'district';
    private $ward = 'ward';
    private $restaurantcategories = 'restaurantcategories';
    private $categoriesofrestaurant = 'categoriesofrestaurant';
    private $food = 'food';
    private $restaurantImage = 'restaurantimage';  
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
   
    function GetById($id)
    {
        $query = $this->db->get_where($this->table, array('restaurantID'=>$id), 1);
        $record = $query->row();
        return $record;
    }
    
    function GetFullByUserId($userId = "")
    {
        $sql = 'select '.$this->table.'.*, ';
        $sql.= $this->address.'.address,'.$this->address.'.provinceID,'.$this->address.'.districtID,'.$this->address.'.wardID, ';
        $sql.= $this->restaurantcategories.'.categoryOfResID ';
        $sql.= 'from '.$this->table;                                                                         
        $sql.= ' left join '.$this->address.' on '.$this->address.'.addressID = '.$this->table.'.addressID';
        $sql.= ' left join '.$this->restaurantcategories.' on '.$this->restaurantcategories.'.restaurantID = '.$this->table.'.restaurantID';
        $sql.= ' where '.$this->table.'.userID = '.$userId.' ';
        $sql.= ' limit 1';
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0)
            return $query->row();
        return null;
    }
    
    function Admin_GetFullById($id = "")
    {
        $sql = 'select '.$this->table.'.*, ';
        $sql.= $this->address.'.address,'.$this->address.'.provinceID,'.$this->address.'.districtID,'.$this->address.'.wardID, ';
        $sql.= $this->restaurantcategories.'.categoryOfResID ';
        $sql.= 'from '.$this->table;                                                                         
        $sql.= ' left join '.$this->address.' on '.$this->address.'.addressID = '.$this->table.'.addressID';
        $sql.= ' left join '.$this->restaurantcategories.' on '.$this->restaurantcategories.'.restaurantID = '.$this->table.'.restaurantID';
        $sql.= ' where '.$this->table.'.restaurantID = '.$id.' ';
        $sql.= ' limit 1';
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0)
            return $query->row();
        return null;
    }
    
    function Details($resId = 0)
    {
        $sql = 'select '.$this->table.'.*, ';
        $sql.= ' CONCAT('.$this->address.'.address, \' \', ';
        $sql.= $this->ward.'.nameWard, \' \', ';
        $sql.= $this->district.'.nameDis, \' \', ';  
        $sql.= $this->province.'.namePro) as address, ';  
        $sql.= $this->categoriesofrestaurant.'.desciptionCOR, '.$this->categoriesofrestaurant.'.categoryOfResID ';
        $sql.= 'from '.$this->table;                                                                         
        $sql.= ' left join '.$this->address.' on '.$this->address.'.addressID = '.$this->table.'.addressID ';
        $sql.= ' left join '.$this->province.' on '.$this->province.'.provinceID = '.$this->address.'.provinceID ';
        $sql.= ' left join '.$this->district.' on '.$this->district.'.districtid = '.$this->address.'.districtID ';
        $sql.= ' left join '.$this->ward.' on '.$this->ward.'.wardid = '.$this->address.'.wardID ';
        $sql.= ' left join '.$this->restaurantcategories.' on '.$this->restaurantcategories.'.restaurantID = '.$this->table.'.restaurantID';
        $sql.= ' left join '.$this->categoriesofrestaurant.' on '.$this->categoriesofrestaurant.'.categoryOfResID = '.$this->restaurantcategories.'.categoryOfResID';
        $sql.= ' where '.$this->table.'.restaurantID = '.$resId.' ';
        $sql.= ' limit 1'; 
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0)
            return $query->row();
        return null;
    }
    
    function GetByUserId($userId)
    {                   
        $query = $this->db->get_where($this->table, array('userID'=>$userId), 1);
        $record = $query->row();
        return $record;
    }
    
    function Admin_GetResCateByResId($id)
    {
        $query = $this->db->get_where($this->restaurantcategories, array('restaurantID'=>$id), 1);
        $record = $query->row();
        return $record;
    }
    
    function ListAllCatByResId($resIdStrArr){
        $sql = 'select distinct '.$this->categoriesofrestaurant.'.* ';
        $sql.= ' from '.$this->restaurantcategories;
        $sql.= ' left join '.$this->categoriesofrestaurant.' on '
                .$this->categoriesofrestaurant.'.categoryOfResID = '.$this->restaurantcategories.'.categoryOfResID ';
        $sql.= ' where '.$this->restaurantcategories.'.restaurantID in ('.$resIdStrArr.')';
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    function ListAllDistByResId($resIdStrArr){
        $sql = 'select distinct '.$this->district.'.* ';
        $sql.= ' from '.$this->table;
        $sql.= ' left join '.$this->address.' on '
                .$this->address.'.addressID = '.$this->table.'.addressID ';
        $sql.= ' left join '.$this->district.' on '
                .$this->district.'.districtID = '.$this->address.'.districtID ';
        $sql.= ' where '.$this->table.'.restaurantID in ('.$resIdStrArr.')';
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    function Search($offset=0, $limit=20, &$count, &$categories, &$districts, $cat, $district, $keyword, $fullTextSearch=true){
        
        $from = 'from '.$this->table;
        $from.= ' left join '.$this->restaurantcategories.' on '.$this->restaurantcategories.'.restaurantID = '.$this->table.'.restaurantID ';
        $from.= ' left join '.$this->address.' on '.$this->address.'.addressID = '.$this->table.'.addressID';
        
        $where = ' where '.$this->table.'.statusRes = 1 ';
        if ($keyword != ''){
            if ($fullTextSearch){
                $where .= ' and MATCH(nameRe, descriptionRes) ';
                $where .= ' AGAINST (\''.$keyword.'\' IN BOOLEAN MODE) ';        
            }
            else{
                $where .= ' and ('.$this->table.'.nameRe like \'%'.$keyword.'%\'';                               
                $where .= ' or '.$this->table.'.descriptionRes like \'%'.$keyword.'%\'';    
                $where .= ' ) ';  
            }   
        }
        if ($cat > 0){
            $where .= ' and ('.$this->restaurantcategories.'.categoryOfResID = '.$cat.') ';                               
        }
        if ($district > 0){
            $where .= ' and ('.$this->address.'.districtID = '.$district.') ';                               
        }
        $select = 'select '.$this->table.'.restaurantID ';
        
        //count
        $ids = '0';
        $query = $this->db->query($select.$from.$where); 
        $count = $query->num_rows();
        if ($count > 0){              
            $resIds = array(0);
            foreach($query->result() as $row){
                array_push($resIds, $row->restaurantID);
            }
            $ids = implode(',', $resIds);
            $categories = $this->ListAllCatByResId($ids);
            $districts = $this->ListAllDistByResId($ids);
        }
        
        $select = 'select '.$this->table.'.restaurantID, '.$this->table.'.nameRe, '.$this->table.'.descriptionRes, '
                    .$this->table.'.rateRe, '.$this->table.'.discount, ';   
        $select.= ' CONCAT('.$this->address.'.address, \' \', ';
        $select.= $this->ward.'.nameWard, \' \', ';
        $select.= $this->district.'.nameDis, \' \', ';    
        $select.= $this->province.'.namePro) as address, ';                             
        $select.= $this->restaurantImage.'.imageUrl ';                                
        $from.= ' left join '.$this->province.' on '.$this->province.'.provinceID = '.$this->address.'.provinceID ';
        $from.= ' left join '.$this->district.' on '.$this->district.'.districtid = '.$this->address.'.districtID ';
        $from.= ' left join '.$this->ward.' on '.$this->ward.'.wardid = '.$this->address.'.wardID ';
        $from.= ' left join '.$this->restaurantImage.' on '.$this->restaurantImage.'.restaurantId = '.$this->table.'.restaurantID and '.$this->restaurantImage.'.imageMain = 1 ';
        $where = ' where '.$this->table.'.restaurantID in ('.$ids.')';
        $order = ' order by '.$this->table.'.rateRe desc ';
        $paging = ' limit '.$limit.' offset '.$offset;  
        $query = $this->db->query($select.$from.$where.$order.$paging);
        return $query->result();
    }
    
    function FindByCategoryPaged($offset=0, $limit=20, &$count, $cat){
        
        $from = 'from '.$this->table;
        $from.= ' left join '.$this->restaurantcategories.' on '.$this->restaurantcategories.'.restaurantID = '.$this->table.'.restaurantID ';
        $from.= ' left join '.$this->address.' on '.$this->address.'.addressID = '.$this->table.'.addressID';
        
        $where = ' where '.$this->table.'.statusRes = 1 ';
        if ($cat > 0){
            $where .= ' and ('.$this->restaurantcategories.'.categoryOfResID = '.$cat.') ';                               
        } 
        $select = 'select '.$this->table.'.restaurantID ';
        
        //count
        $ids = '0';
        $query = $this->db->query($select.$from.$where);
        $count = $query->num_rows();
        if ($count > 0){              
            $resIds = array(0);
            foreach($query->result() as $row){
                array_push($resIds, $row->restaurantID);
            }
            $ids = implode(',', $resIds);             
        }
        
        $select = 'select '.$this->table.'.restaurantID, '.$this->table.'.nameRe, '.$this->table.'.descriptionRes, '
                    .$this->table.'.rateRe, '.$this->table.'.discount, ';   
        $select.= ' CONCAT('.$this->address.'.address, \' \', ';
        $select.= $this->ward.'.nameWard, \' \', ';
        $select.= $this->district.'.nameDis, \' \', ';  
        $select.= $this->province.'.namePro) as address, ';                             
        $select.= $this->restaurantImage.'.imageUrl ';                             
        $from.= ' left join '.$this->province.' on '.$this->province.'.provinceID = '.$this->address.'.provinceID ';
        $from.= ' left join '.$this->district.' on '.$this->district.'.districtid = '.$this->address.'.districtID ';
        $from.= ' left join '.$this->ward.' on '.$this->ward.'.wardid = '.$this->address.'.wardID ';
        $from.= ' left join '.$this->restaurantImage.' on '.$this->restaurantImage.'.restaurantId = '.$this->table.'.restaurantID and '.$this->restaurantImage.'.imageMain = 1 ';
        $where = ' where '.$this->table.'.restaurantID in ('.$ids.')';
        $order = ' order by '.$this->table.'.rateRe desc ';
        $paging = ' limit '.$limit.' offset '.$offset;  
        $query = $this->db->query($select.$from.$where.$order.$paging);
        return $query->result();
    }
    
    function FindTopRank($offset=0, $limit=20, &$count){
        $from = 'from '.$this->table;
        $where = ' where '.$this->table.'.statusRes = 1 ';
        
        $select = 'select 1 ';
        //count
        $query = $this->db->query($select.$from.$where);
        $count = $query->num_rows();
        
        $select = 'select '.$this->table.'.restaurantID, '.$this->table.'.nameRe, '.$this->table.'.descriptionRes, '
                    .$this->table.'.rateRe, '.$this->table.'.discount, ';   
        $select.= ' CONCAT('.$this->address.'.address, \' \', ';
        $select.= $this->ward.'.nameWard, \' \', ';
        $select.= $this->district.'.nameDis, \' \', ';     
        $select.= $this->province.'.namePro) as address, ';                             
        $select.= $this->restaurantImage.'.imageUrl ';     
        $from.= ' left join '.$this->address.' on '.$this->address.'.addressID = '.$this->table.'.addressID';
        $from.= ' left join '.$this->province.' on '.$this->province.'.provinceID = '.$this->address.'.provinceID ';
        $from.= ' left join '.$this->district.' on '.$this->district.'.districtid = '.$this->address.'.districtID ';
        $from.= ' left join '.$this->ward.' on '.$this->ward.'.wardid = '.$this->address.'.wardID ';
        $from.= ' left join '.$this->restaurantImage.' on '.$this->restaurantImage.'.restaurantId = '.$this->table.'.restaurantID and '.$this->restaurantImage.'.imageMain = 1 ';
        
        $order = ' order by '.$this->table.'.rateRe desc ';
        $paging = ' limit '.$limit.' offset '.$offset;
        
        $query = $this->db->query($select.$from.$where.$order.$paging);
        return $query->result();
    }
    
    function FindTopRelation($offset=0, $limit=20, &$count, $cateId=null){
        $from = 'from '.$this->table;
        $from.= ' left join '.$this->restaurantcategories.' on '.$this->restaurantcategories.'.restaurantID = '.$this->table.'.restaurantID ';
        $where = ' where '.$this->table.'.statusRes = 1 ';
        if ($cateId != null)
            $where .= ' and '.$this->restaurantcategories.'.categoryOfResID = '.$cateId.' ';
        
        $select = 'select 1 ';
        //count
        $query = $this->db->query($select.$from.$where);
        $count = $query->num_rows();
        
        $select = 'select '.$this->table.'.restaurantID, '.$this->table.'.nameRe, '.$this->table.'.descriptionRes, '
                    .$this->table.'.rateRe, '.$this->table.'.discount, ';   
        $select.= ' CONCAT('.$this->address.'.address, \' \', ';
        $select.= $this->ward.'.nameWard, \' \', ';
        $select.= $this->district.'.nameDis, \' \', ';      
        $select.= $this->province.'.namePro) as address, ';                             
        $select.= $this->restaurantImage.'.imageUrl ';   
        $from.= ' left join '.$this->address.' on '.$this->address.'.addressID = '.$this->table.'.addressID';
        $from.= ' left join '.$this->province.' on '.$this->province.'.provinceID = '.$this->address.'.provinceID ';
        $from.= ' left join '.$this->district.' on '.$this->district.'.districtid = '.$this->address.'.districtID ';
        $from.= ' left join '.$this->ward.' on '.$this->ward.'.wardid = '.$this->address.'.wardID '; 
        $from.= ' left join '.$this->restaurantImage.' on '.$this->restaurantImage.'.restaurantId = '.$this->table.'.restaurantID and '.$this->restaurantImage.'.imageMain = 1 ';
         
        $order = ' order by '.$this->table.'.rateRe desc ';
        $paging = ' limit '.$limit.' offset '.$offset;
        
        $query = $this->db->query($select.$from.$where.$order.$paging);
        return $query->result();
    }
    
    function FindCountDown($offset=0, $limit=20, &$count){
        $from = 'from '.$this->table;                     
        $where = ' where '.$this->table.'.discount > 0 ';
        
        $select = 'select 1 ';
        //count
        $query = $this->db->query($select.$from.$where);
        $count = $query->num_rows();
        
        $select = 'select '.$this->table.'.restaurantID, '.$this->table.'.nameRe, '.$this->table.'.descriptionRes, '
                    .$this->table.'.rateRe, '.$this->table.'.discount, ';   
        $select.= ' CONCAT('.$this->address.'.address, \' \', ';
        $select.= $this->ward.'.nameWard, \' \', ';
        $select.= $this->district.'.nameDis, \' \', ';     
        $select.= $this->province.'.namePro) as address, ';                             
        $select.= $this->restaurantImage.'.imageUrl ';     
        $from.= ' left join '.$this->address.' on '.$this->address.'.addressID = '.$this->table.'.addressID';
        $from.= ' left join '.$this->province.' on '.$this->province.'.provinceID = '.$this->address.'.provinceID ';
        $from.= ' left join '.$this->district.' on '.$this->district.'.districtid = '.$this->address.'.districtID ';
        $from.= ' left join '.$this->ward.' on '.$this->ward.'.wardid = '.$this->address.'.wardID ';
        $from.= ' left join '.$this->restaurantImage.' on '.$this->restaurantImage.'.restaurantId = '.$this->table.'.restaurantID and '.$this->restaurantImage.'.imageMain = 1 ';
                                                         
        $order = ' order by '.$this->table.'.discount desc ';
        $paging = ' limit '.$limit.' offset '.$offset;
        
        $query = $this->db->query($select.$from.$where.$order.$paging);
        return $query->result();     
    }
    
    function Create($data)
    {   
        $restaurant = $data['restaurant'];
        $cate = $data['cate'];
        $add = $data['add'];
        //Bat dau trans
        $this->db->trans_begin(); 
        //add address
        if ($add != null) {
            //Insert du lieu vao bang address
            $this->db->insert($this->address, $add);      
            $restaurant['addressID'] = $this->db->insert_id();
        }
        //Insert du lieu vao bang restaurant
        $this->db->insert($this->table, $restaurant);
        //Tráº£ ra user_id vua insert
        $restaurantID = $this->db->insert_id();
        if($restaurantID > 0)
        {
            //Neu insert thanh cong vao bang restaurantcategories             
            $cate['restaurantID'] =  $restaurantID;   
            $this->db->insert($this->restaurantcategories, $cate);
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
    
    function Update($id, $data)
    {                
        $restaurant = $data['restaurant'];
        $cate = $data['cate'];
        $add = $data['add'];
            
        $this->db->trans_begin();
        $this->db->update($this->address, $add, array('addressID'=>$restaurant['addressID']));   
        $this->db->update($this->restaurantcategories, $cate, array('restaurantID'=>$id)); 
        $this->db->update($this->table, $restaurant, array('restaurantID'=>$id));       
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
        $restaurant = $this->Admin_GetById($id);
        //Bat dau trans
        $this->db->trans_begin();                                   
        $this->db->delete($this->restaurantcategories, array('restaurantID'=>$id)); 
        $this->db->delete($this->address, array('addressID'=>$restaurant->addressID)); 
        $this->db->delete($this->food, array('restaurantID'=>$id)); 
        $this->db->delete($this->table, array('restaurantID'=>$id)); 
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
    
    public function Report_ThongKeChung(){
        $select = 'select ';
        $select .= '(select count(1) from '.$this->table.' where  statusRes = 0) as numberNotActive, ';
        $select .= '(select count(1) from '.$this->table.' where  statusRes = 1) as numberActived, ';
        $select .= '(select count(1) from '.$this->table.') as total';
        $query = $this->db->query($select);
        if ($query->num_rows() > 0)
            return $query->row();
        return null;
    }
}

?>
<?php
class Users extends CI_Controller 
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('date');
        $this->load->database();
        $this->load->library('session');    
        $this->load->library('pagination');
        $this->load->model('usersModel', '', TRUE);
        $this->load->model('addressModel', '', TRUE);
        $this->load->model('imageModel', '', TRUE);
        $user = $this->session->userdata('user');
        $level = $this->session->userdata('level');
        if(!isset($user) || $user == '' || !isset($level) || $level != 2)
        {
            redirect(base_url().'/home');
        }
    }
    
    function index($level=0, $offset = 0)
    {
        $level = intval($level);
        $offset = intval($offset);
        $data = array();
        $user = $this->session->userdata('user');  
                                
        $rows = 0;
        switch ($level){
            case 2:
            $data['title'] = 'Danh sách user quản trị';
            break;     
            case 1:
            $data['title'] = 'Danh sách user nhà hàng';
            break;
            case 0:
            $data['title'] = 'Danh sách thực khách';
            break;
        }                                
        $result = $this->usersModel->Admin_GetUser($level, 10, $offset);
        $rows = $this->usersModel->Count_By($level);
        
        $config['base_url'] = site_url().'/users/index/';
        $config['total_rows'] = $rows;
        $config['per_page'] = 10;
        $config['full_tag_open'] = "<div class='pag'>";
        $config['full_tag_close'] = "</div>";
        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();
                
        $data['user'] = array('user' => $user);
        $data['model'] = array(
            'level' => $level,
            'result' => $result,
            'rows' => $rows,
            'pagination' => $pagination);
        $data['content'] = 'admin/users/all.phtml';
        
        $this->load->view('admin/layout/layout.phtml', $data);  
    }
    
    public function Add($level=0)
    {
        $user = $this->session->userdata('user');                            
                                                 
        $data['user'] = array('user' => $user);
        $data['content'] = 'admin/users/edit.phtml';
        switch ($level){
            case 2:
            $data['title'] = 'Thêm user quản trị';
            break;     
            case 1:
            $data['title'] = 'Thêm user nhà hàng';
            break;
            case 0:
            $data['title'] = 'Thêm thực khách';
            break;
        }
        $model = array(
             'error' => '',
             'level' => $level,
             'address' => $this->addressModel->ListAll(),
             'userLevel' => $level
        );
        $data['model'] = $model;
  
        if($this->input->post('submit'))
        {       
            //Lay du lieu tu forn dong thoi gan bien du gia tri 
            $model['userLevel'] = $userLevel = strip_tags($this->input->post('userLevel')); 
            $model['userName'] = $userName = strip_tags($this->input->post('userName'));
            $model['userMail'] = $userMail = strip_tags($this->input->post('userMail'));
            $model['userActived'] = $userActived = strip_tags($this->input->post('userActived'));
            $userPass = strip_tags($this->input->post('userPass'));
            $userPassRe = strip_tags($this->input->post('userPassRe'));
            $model['memName'] = $memName = strip_tags($this->input->post('memName'));
            $model['memBirthDay'] = $memBirthDay = strip_tags($this->input->post('memBirthDay'));
            $model['memGender'] = $memGender = strip_tags($this->input->post('memGender'));
            $model['addressID'] = $addressID = strip_tags($this->input->post('addressID'));
            $model['imageID'] = $imageID = strip_tags($this->input->post('imageID'));
            
            $data['model'] = $model;   
            //kiem tra du lieu
            $error = '';
            $ok = 1;
            if($userLevel == '')
            {
                $error .= $this->Error('Chưa chọn cấp cho user');
                $ok = 0;
            }
            if($userName == '')
            {
                $error .= $this->Error('Chưa có tên đăng nhập');
                $ok = 0;
            }
            if($userPass == '')
            {
                $error .= $this->Error('Chưa có mật khẩu');
                $ok = 0;
            }
            if($userPass != $userPassRe)
            {
                $error .= $this->Error('Mật khẩu nhập kiểm tra không khớp');
                $ok = 0;
            }
            if($memName == '')
            {
                $error .= $this->Error('Chưa nhập tên');
                $ok = 0;
            }
            if ($ok == 1)
            {
                //Tao mang chua thong tin ve user
                $dataAdd_user = array(
                                'userName'     =>  $userName,
                                'userMail'     =>  $userMail,
                                'userPass'     =>  md5($userPass),
                                'userPassRe'  =>  md5($userPass),
                                'userActived' => $userActived,
                                'userLevel'    =>  $userLevel
                                );
                //Tao mang chua thong tin chi tiet ve user
                $dataAdd_mem =  array(
                                'memName'      =>  $memName,
                                'memBirthDay'  =>  $memBirthDay,
                                'memGender'   =>  $memGender,
                                'addressID'   =>  $addressID,
                                'imageID'   =>  $imageID
                                );
                //Tao mang chua toan  bo du lieu de dua vao model
                $dataAdd = array('user'=>$dataAdd_user, 'mem'=>$dataAdd_mem);
                
                if ($this->usersModel->Create($dataAdd))
                {
                    //Neu luu thanh cong
                    redirect(base_url('users/index/'.$level));
                }
                else
                {
                    //Nguoc lai neu khong luu duoc
                      
                    $data['model']['error'] = $this->Error('Không tạo được user!');                                      
                    $this->load->view('admin/layout/layout.phtml', $data);  
                }
            } 
            else
            {
                $data['model']['error'] = $error;                                                            
                $this->load->view('admin/layout/layout.phtml', $data);  
            }           
        }
        else
        {                                                                                     
             $this->load->view('admin/layout/layout.phtml', $data);  
        }
    }
    
    function Edit($id = 0)
    {
        $this->check_id($id, 'users');                   
                                       
        $user = $this->usersModel->Admin_GetUserById($id);
        if ($user == null)
            return redirect(base_url("users")); 
              
        $data['user'] = array('user' => $this->session->userdata('user'));
        $data['content'] = 'admin/users/edit.phtml';

        switch ($user->userLevel){
            case 2:
            $data['title'] = 'Cập nhật user quản trị';
            break;     
            case 1:
            $data['title'] = 'Cập nhật user nhà hàng';
            break;
            case 0:
            $data['title'] = 'Cập nhật thực khách';
            break;
        }
        $model = array(
             'error' => '',
             'level' => $user->userLevel,
             'address' => $this->addressModel->ListAll()
        );
              
        //Lay du lieu tu forn dong thoi gan bien du gia tri 
        $model['userID'] = $user->userID; 
        $model['userLevel'] = $user->userLevel; 
        $model['userName'] = $user->userName;
        $model['userMail'] = $user->userMail;  
        $model['userActived'] = $user->userActived;   
        $model['memName'] = $user->memName;
        $model['memBirthDay'] = $user->memBirthDay;
        $model['memGender'] = $user->memGender;
        $model['addressID'] = $user->addressID;
        $model['imageID'] = $user->imageID;      
        
        $data['model']  = $model;
        if($this->input->post('submit'))
        {                           
            //Lay du lieu tu forn dong thoi gan bien du gia tri 
            $model['userLevel'] = $userLevel = strip_tags($this->input->post('userLevel')); 
            $model['userName'] = $userName = strip_tags($this->input->post('userName'));
            $model['userMail'] = $userMail = strip_tags($this->input->post('userMail'));
            $model['userActived'] = $userActived = strip_tags($this->input->post('userActived'));
            $userPass = strip_tags($this->input->post('userPass'));
            $userPassRe = strip_tags($this->input->post('userPassRe'));
            $model['memName'] = $memName = strip_tags($this->input->post('memName'));
            $model['memBirthDay'] = $memBirthDay = strip_tags($this->input->post('memBirthDay'));
            $model['memGender'] = $memGender = strip_tags($this->input->post('memGender'));
            $model['addressID'] = $addressID = strip_tags($this->input->post('addressID'));
            $model['imageID'] = $imageID = strip_tags($this->input->post('imageID'));

            $data['model']  = $model;
            //kiem tra du lieu
            //kiem tra du lieu
            $error = '';
            $ok = 1;
            if($userLevel == '')
            {
                $error .= $this->Error('Chưa chọn cấp cho user');
                $ok = 0;
            }
            if($userName == '')
            {
                $error .= $this->Error('Chưa có tên đăng nhập');
                $ok = 0;
            }
            if ($userPass != ''){
                if($userPass != $userPassRe)
                {
                    $error .= $this->Error('Mật khẩu nhập kiểm tra không khớp');
                    $ok = 0;
                }
                else{
                    $userPass = $userPassRe = md5($userPass);
                }
            }
            else {
                $userPass = $userPassRe = $user->userPass;
            }

            if($memName == '')
            {
                $error .= $this->Error('Chưa nhập tên');
                $ok = 0;
            }
            if ($ok == 1)
            {
                //Tao mang chua thong tin ve user
                $dataEdit_user = array(
                                'userName'     =>  $userName,
                                'userMail'     =>  $userMail,  
                                'userPass'     =>  $userPass,
                                'userPassRe'  =>  $userPassRe,
                                'userLevel'    =>  $userLevel ,
                                'userActived'    =>  $userActived
                                );
                //Tao mang chua thong tin chi tiet ve user
                $dataEdit_mem =  array(
                                'memName'      =>  $memName,
                                'memBirthDay'  =>  $memBirthDay,
                                'memGender'   =>  $memGender,
                                'addressID'   =>  $addressID,
                                'imageID'   =>  $imageID
                                );
                //Tao mang chua toan  bo du lieu de dua vao model
                $dataEdit = array('user'=>$dataEdit_user, 'mem'=>$dataEdit_mem);
                
                if ($this->usersModel->Update($id, $dataEdit))
                {
                    //Neu luu thanh cong      
                    redirect(base_url('users/index/'.$level));
                }
                else
                {
                    //Nguoc lai neu khong luu duoc
                      
                    $data['model']['error'] = $this->Error('Không tạo được user!');                                      
                    $this->load->view('admin/layout/layout.phtml', $data);
                }
            } 
            else
            {
                $data['model']['error'] = $error;                                                            
                $this->load->view('admin/layout/layout.phtml', $data);
            }           
        }
        else
        {                                                                                                        
            $this->load->view('admin/layout/layout.phtml', $data);
        }
    }
    
    public function Delete($id, $level)
    {                      
        $this->check_id($id, 'users');
        $this->usersModel->Delete($id);
        redirect(base_url('users/index/'.$level));
    }
    
    public function check_id($id, $controler)
    {
        if(is_numeric($id) == FALSE)
        {
            redirect(base_url($controler));
        }
    }
    public function Error($value)
    {
        return '<br /><font color=red>- '.$value.'</font>';
    }
}
?>

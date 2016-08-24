<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
  
class Home extends CI_Controller {

	 public function __construct(){
        parent::__construct();
        $this->load->helper('url');        
        $this->load->library('pagination');
        $this->load->library('session');
        $this->load->library('DateUtils');
        $this->load->library('UriUtils');
        $this->load->library('Xulychuoi');
        $this->load->model('addressModel', '', TRUE);
        $this->load->model('usersModel', '', TRUE);
        $this->load->model('restaurantModel', '', TRUE);
        $this->load->model('newsModel', '', TRUE);
        $this->load->model('categoriesOfRestaurantModel', '', TRUE);
        $this->load->database();
    }
  
    function getConfig(){
        return array(
            'per_page' => 20,
            'full_tag_open' => "<ul class='pagination'>",
            'full_tag_close' => "</ul>",
            'num_tag_open' => '<li>',
            'num_tag_close' => '</li>',
            'cur_tag_open' => "<li class='disabled'><li class='active'><a href='#'>",
            'cur_tag_close' => "<span class='sr-only'></span></a></li>",
            'next_tag_open' => "<li>",
            'next_tagl_close' => "</li>",
            'prev_tag_open' => "<li>",
            'prev_tagl_close' => "</li>",
            'first_tag_open' => "<li>",
            'first_tagl_close' => "</li>",
            'last_tag_open' => "<li>",
            'last_tagl_close' => "</li>"
        );       
    }  
    
    public function index() {               
        $user = $this->session->userdata('user');
        $level = $this->session->userdata('level');
        $count = 0;
        
        $data = array(
            'user' => $this->session->userdata('user'),
            'fullname' => $this->session->userdata('fullname'), 
            'level' => $this->session->userdata('level'),    
            'categoryModels' => $this->categoriesOfRestaurantModel->ListByStatus(1),
            'model' => array(
                'resMaxRankModel' => array(
                    'items' => $this->restaurantModel->FindTopRank(0, 4, $count),
                    'totalCount' => $count,
                    'title' => 'Điểm đến uy tín',
                    'urlAllView' => base_url().'home/home_view_all/diem-den-uy-tin/1',
                ),
                'resCountdownModel' => array(
                    'items' => $this->restaurantModel->FindCountDown(0, 8, $count),
                    'totalCount' => $count,
                    'title' => 'Điểm đến ưu đãi',
                    'urlAllView' => base_url().'home/home_view_all/diem-den-uu-dai/2',
                ),
                'newsModel' => array(
                    'items' => $this->newsModel->FindNewsForHome(0, 3, $count),
                    'totalCount' => $count,
                    'title' => 'Tin tức'
                ),                                                      
            )
        );                    
        $data['content'] = 'site/home/index/index.phtml';      

        $this->load->view('site/layout/layout.phtml', $data);
    }
   
	public function search($cat=0, $district=0, $key='', $offset=0) { 
        $keyword = urldecode($key);
        $isFullTextSeach = true;//tim kiếm toàn văn
        $keySearchDb = $keyword;
        if ($isFullTextSeach){
           //$keySearchDb = $this->prepareFullTextSearch($keyword); 
           //print_r($keySearchDb);
        }
        $user = $this->session->userdata('user');
        $level = $this->session->userdata('level');
        $count = 0;
        $categories = array();
        $districts = array();
        $config = $this->getConfig();
        $data = array(
            'title'=> 'Tìm kiếm '.$keyword,
            'user' => $this->session->userdata('user'),
            'fullname' => $this->session->userdata('fullname'), 
            'level' => $this->session->userdata('level'),
            'categoryModels' => $this->categoriesOfRestaurantModel->ListByStatus(1),
            'model' => array(
                'base_url' => base_url(),
                'cat' => $cat,
                'district' => $district,
                'offset' => $offset,
                'keyword' => $keyword,
                'items' => $this->restaurantModel->Search($offset, $config['per_page'], $count, $categories, 
                                $districts, $cat, $district, $keySearchDb, $isFullTextSeach),
                'categories' => $categories,
                'districts' => $districts,
                'count' => $count,                                                         
            )
        ); 
           
        $config['base_url'] = $this->uriutils->BuildSearchUrl($cat, $district, $key);
        $config['total_rows'] = $count;  
         
        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();   
        $data['pagination'] = $pagination;     
        $data['content'] = 'site/home/search/index.phtml';     

        $this->load->view('site/layout/layout.phtml', $data);
    }
    
    public function category($cat=0, $offset=0) { 
        $category = $this->categoriesOfRestaurantModel->GetById($cat);
        $user = $this->session->userdata('user');
        $level = $this->session->userdata('level');
        
        $count = 0;      
        $config = $this->getConfig();
        $data = array(
            'title'=> $category->nameCOR,
            'user' => $this->session->userdata('user'),
            'fullname' => $this->session->userdata('fullname'), 
            'level' => $this->session->userdata('level'),
            'categoryModels' => $this->categoriesOfRestaurantModel->ListByStatus(1),
            'model' => array(      
                'items' => $this->restaurantModel->FindByCategoryPaged($offset, $config['per_page'], $count, $cat),
                'categoryName'=> $category->nameCOR,                                        
            )
        ); 
           
        $config['base_url'] = base_url().'home/category/'.$cat;
        $config['total_rows'] = $count;  
         
        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();   
        $data['pagination'] = $pagination;     
        $data['content'] = 'site/home/category/index.phtml';     

        $this->load->view('site/layout/layout.phtml', $data);
    }
             
    public function home_view_all($title='', $type=1, $offset=0) {     
        $type = intval($type);
        $user = $this->session->userdata('user');
        $level = $this->session->userdata('level');
        
        $count = 0;      
        $config = $this->getConfig();
        $data = array(
            'title'=> $type==1 ? 'Điểm đến uy tín' : 'Điểm đến ưu đãi',
            'user' => $this->session->userdata('user'),
            'fullname' => $this->session->userdata('fullname'), 
            'level' => $this->session->userdata('level'),
            'categoryModels' => $this->categoriesOfRestaurantModel->ListByStatus(1),
            'model' => array(      
                'items' => $type==1
                        ? $this->restaurantModel->FindTopRank($offset, $config['per_page'], $count)
                        : $this->restaurantModel->FindCountDown($offset, $config['per_page'], $count),
                'categoryName'=>  $type==1 ? 'Điểm đến uy tín' : 'Điểm đến ưu đãi',                                        
            )
        ); 
           
        $config['base_url'] = base_url().($type == 1 ? 'home/home_view_all/diem-den-uy-tin/1/' : 'home/home_view_all/diem-den-uu-dai/1/');
        $config['total_rows'] = $count;  
         
        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();   
        $data['pagination'] = $pagination;     
        $data['content'] = 'site/home/category/index.phtml';     

        $this->load->view('site/layout/layout.phtml', $data);  
    }
    public function news($cat=0, $offset=0) { 
        $user = $this->session->userdata('user');
        $level = $this->session->userdata('level');
        
        $count = 0;      
        $config = $this->getConfig();
        $data = array(
            'title'=> $this->GetNameOfTypeNews($cat),
            'user' => $this->session->userdata('user'),
            'fullname' => $this->session->userdata('fullname'), 
            'level' => $this->session->userdata('level'),
            'categoryModels' => $this->categoriesOfRestaurantModel->ListByStatus(1),
            'model' => array(      
                'items' => $this->newsModel->FindNewsPaged($offset, $config['per_page'], $count, $cat),
                'categoryName'=> $this->GetNameOfTypeNews($cat),                                        
            )
        ); 
           
        $config['base_url'] = $this->uriutils->BuildMenuStaticUrl($cat);
        $config['total_rows'] = $count;  
         
        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();   
        $data['pagination'] = $pagination;     
        $data['content'] = 'site/news/index.phtml';     

        $this->load->view('site/layout/layout.phtml', $data);
    }
    
    public function newsDetails($newsId=0){ 
        $news = $this->newsModel->GetById($newsId);   
        $data = array(
            'title' => $news->titleNews, 
            'user' => $this->session->userdata('user'), 
            'fullname' => $this->session->userdata('fullname'), 
            'level' => $this->session->userdata('level'), 
            'content' => 'site/news/view.phtml', 
            'categoryModels' => $this->categoriesOfRestaurantModel->ListByStatus(1),
            'userModel' => array( 
            ),                                        
            'model' => array(
                'news' => $news
            )
        );                                                  
        $this->load->view('site/layout/layout.phtml', $data);
    }
            
    /***********************Ham index liet ke giao dien trang home****************************/
    public function login()
    {        
        $ok = 1;
        $user = $this->input->post('username');
        $pass = $this->input->post('password');
        $msg = '';
        if($user == '') {
            $msg = 'Chưa nhập tên đăng nhập/email.';
            $ok = 0;
        } 
        if($pass == '') {
            $msg = 'Chưa nhập mật khẩu';
            $ok = 0;
        }
          
        if($ok == 1) {
            //$query_login = $this->db->query("SELECT * FROM users WHERE (userName = '$user' OR userMail = '$user') AND userPass = '$pass'");
            $flag = false;
            
            $query_login = $this->usersModel->CheckUserInLogin($user, $pass, $flag, $msg); 

            if($flag) {                           
                $dataSet = array(
                        'userID' => $query_login->userID,
                        'user' => $query_login->userName,
                        'fullname' => $query_login->memName,
                        'level' => $query_login->userLevel,
                        'isActived' => $query_login->userActived
                ); 
                
                if($dataSet['isActived'] == 0) {
                    echo $this->returnError('Người dùng chưa kích hoạt');
                }else
                if($dataSet['level'] == 2 || $dataSet['level'] == 1 || $dataSet['level'] == 0) {
                    $this->session->set_userdata($dataSet);
                    echo $this->returnSuccess('Đăng nhập thành công');
                }
                else
                {                                                               
                    echo $this->returnError('Only admin can login');
                } 
            }else{                                                               
                echo $this->returnError($msg);
            }
        }else{                             
            echo $this->returnError($msg);
        }
    }
            
    /***********************Ham index liet ke giao dien trang home****************************/
    public function logout()
    {     
        $this->session->sess_destroy();
        redirect(base_url().'home');      
    }
    
    public function signup(){                     
        $user = $this->session->userdata('user');
        $level = $this->session->userdata('level');
        if ($user != '')
            redirect(base_url('home/index'));
        $model = array(
            'error' => '',
            'province' => $this->addressModel->ListAllProvince(),
        );
        
        $submit = $this->input->post('submit');
        if ($submit){
            $model['provinceID'] = $provinceID = $this->input->post('provinceID');
            $model['districtID'] = $districtID = $this->input->post('districtID');
            $model['wardID'] = $wardID = $this->input->post('wardID'); 
            
            $model['userLevel'] = $userLevel = strip_tags($this->input->post('userLevel')); 
            $model['userName'] = $userName = strip_tags($this->input->post('userName'));
            $model['userMail'] = $userMail = strip_tags($this->input->post('userMail'));  
            $userPass = strip_tags($this->input->post('userPass'));
            $userPassRe = strip_tags($this->input->post('userPassRe'));
            
            $model['memName'] = $memName = strip_tags($this->input->post('memName'));
            $model['memBirthDay'] = $memBirthDay = strip_tags($this->input->post('memBirthDay'));
            $model['memGender'] = $memGender = strip_tags($this->input->post('memGender'));
            $model['addressID'] = $addressID = strip_tags($this->input->post('addressID'));
            $model['address'] = $address = strip_tags($this->input->post('address'));
            $model['imageID'] = $imageID = strip_tags($this->input->post('imageID'));
            
            $ok = 1;
            $error = '';
            $userActived = 0;
            
            //nhà hàng tự động active để nhà hàng vào chỉnh, sửa đổi thông tin
            //người dùng tự động active để có thể vào đặt chỗ luôn
            
            if ($userLevel == 1 || $userLevel == 0)
                $userActived = 1;
                
            if ($memName == '')
            {
                $ok = 0;
                $error .= 'Chưa nhập tên đầy đủ<br />';
            }
            if ($userName == '')
            {
                $ok = 0;
                $error .= 'Chưa nhập tên đăng nhập<br />';
            } 
            else if (strlen($userName)<6) {
                $ok = 0;
                $error .= 'Tên đăng nhập phải ít nhất 6 ký tự<br />';
            } else if (preg_match('/\s/',$userName)>0) 
            {
                $ok = 0;
                $error .= 'Tên đăng nhập không được chứa dấu cách<br />';
            }
            else {
                $userExisted = $this->usersModel->CheckNameOrEmailExisted($userName);
                if ($userExisted){
                   $ok = 0;
                   $error .= 'Tên đăng nhập đã tồn tại<br />'; 
                }
            }
            if ($userMail == '')
            {
                $ok = 0;
                $error .= 'Chưa nhập email<br />';
            }
            else if (!filter_var($userMail, FILTER_VALIDATE_EMAIL)){
                $ok = 0;
                $error = "Chưa đúng dạng email<br />"; 

            }
            else{
                $userExisted = $this->usersModel->CheckNameOrEmailExisted($userMail);
                if ($userExisted){
                   $ok = 0;
                   $error .= 'Email đã tồn tại<br />'; 
                }
            }
            if ($userPass == '')
            {
                $ok = 0;
                $error .= 'Chưa nhập mật khẩu<br />';
            }
            else if (strlen($userPass)<6) {
                $ok = 0;
                $error .= 'Mật khẩu phải ít nhất 6 ký tự<br />';
            } 
            else {
                if ($userPass != $userPassRe)
                {
                    $ok = 0;
                    $error .= 'Mật khẩu nhập lại không khớp<br />';
                }
            }
            if ($provinceID == '')
            {
//                $ok = 0;
//                $error .= 'Chưa chọn tỉnh/ thành phố<br />';
            }
            if ($address == '')
            {
//                $ok = 0;
//                $error .= 'Chưa nhập địa chỉ<br />';
            }

            if ($memBirthDay == '') {
                $memBirthDay=date("d/m/y");
            } 
            else {
                $memBirthDay = $this->dateutils->ConvertToDatetime($memBirthDay, 'd/m/Y'); 
        
                if (new DateTime('now') < $memBirthDay ){
                    $ok = 0;
                    $error .= 'Ngày sinh phải trước ngày hiện tại<br />';
                }                
            }

            if ($ok == 0){
                $model['error'] = $error; 
                $model['district'] = $this->addressModel->FindDistrictByProvinceId($provinceID);
                $model['ward'] = $this->addressModel->FindWardByProvinceId($districtID);
            }
            else {
				$userPass = md5($userPass);
                $addInfo = array(
                    'user' => array(
                        'userName' => $userName,
                        'userMail' => $userMail,
                        'userPass' => $userPass,
                        'userPassRe' => $userPass,
                        'userLevel' => $userLevel,
                        'userActived' => $userActived
                    ),
                    'mem' => array(
                        'memName'=> $memName,
                        'memBirthDay' => $this->dateutils->VnStrDatetimeToDb($memBirthDay, 'd/m/Y'),
                        'memGender'=> $memGender
                    ),
                    'add' => array(
                        'address' => $address,
                        'provinceID' => $provinceID,
                        'districtID' => $districtID,
                        'wardID' => $wardID,
                        'statusAdd' => 1
                    )
                );
                
                if ($this->usersModel->Create($addInfo)){
                    if ($userLevel == 1)//nhà hàng thì tự động login và về trang sửa thông tin nhà hàng và chờ duyệt
                    {
                         $dataSet = array(
                            'title' => 'Đăng ký tài khoản',
                            'user' => $userName,
                            'fullname' => $memName,
                            'level' => $userLevel,
                            'isActived' => $userActived,
                         );
                         $this->session->set_userdata($dataSet);
                         redirect(base_url('user_profile/index'));
                    }
                                        
                    $data = array(
                         'content' => 'site/user/user/signupfinally.phtml',
                         'user'=> $user,
                         'level'=> $level,
                         'model' => array(
                             'error' => 'Đăng kí thành công'
                         )
                     );
                    return $this->load->view('site/layout/layout.phtml', $data);
                }
                else{
                    $model['error'] = 'Có lỗi trong quá trình đăng kí'; 
                    $model['district'] = $this->addressModel->FindDistrictByProvinceId($provinceID);
                    $model['ward'] = $this->addressModel->FindWardByProvinceId($districtID);
                }
            }
            //
        }
         
        $data = array(
             'content' => 'site/user/user/signup.phtml',
             'user'=> $user,
             'level'=> $level,
             'model' => $model,
            'categoryModels' => $this->categoriesOfRestaurantModel->ListByStatus(1),
        );
        
        $this->load->view('site/layout/layout.phtml', $data);
    }
      
    public function getDistrictByProvinceId($provinceId=0)
    {        
        $districts = $this->addressModel->FindDistrictByProvinceId($provinceId);
        echo $this->returnSuccess(json_encode($districts));
    }
     
    public function getWardByDistrictId($districtId=0)
    {        
        $wards = $this->addressModel->FindWardByProvinceId($districtId);
        echo $this->returnSuccess(json_encode($wards));
    }
            
    /***********************Ham index liet ke giao dien trang home****************************/  
                    
    public function returnError($msg){
        return json_encode(array(
                    'text' => $msg,
                    'type' => 'error'
            ));   
    }
    
    public function returnSuccess($msg){        
        return json_encode(array(
                    'text' => $msg,
                    'type' => 'success'
            ));        
    }
    
    function GetNameOfTypeNews($cat=0){
        $typeNew = '';
        switch($cat){
            case 31:
                $typeNew.= 'Tin cập nhật';
                break;
            case 32:
                $typeNew.= 'Sự kiện';
                break;
            case 33:
                $typeNew.= 'Khuyến mại';
                break;
            case 34:
                $typeNew.= 'Trải nghiệm';
                break; 
            default:
                $typeNew.= 'Tất cả';
                break;
        }
        return $typeNew;
    }
    
}